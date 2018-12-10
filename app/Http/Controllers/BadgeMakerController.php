<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Util\BadgeCreator;
use App\Helper;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class BadgeMakerController extends Controller
{
    private const BADGE_ITEMS_SESSION_KEY = 'badge_items';

    private static function getSources() {
        $sources = [
            [
                'key' => 'helpers',
                'label' => __('people.helpers'),
                'allowed' => Auth::user()->can('list', Helper::class),
            ],
            [
                'key' => 'file',
                'label' => __('app.file'),
                'allowed' => true,
            ],
        ];
        return collect($sources)->where('allowed', true)->pluck('label', 'key');
    }

    public function index(Request $request) {
        $request->session()->forget(self::BADGE_ITEMS_SESSION_KEY);

        $sources = self::getSources();
        $source = $request->has('source') && $sources->keys()->contains($request->source) 
            ? $request->source
            : $sources->keys()->first();

        return view('badges.index', [
            'source' => $source,
            'sources' => $sources,
        ]);
    }

    public function selection(Request $request) {
        $validator = Validator::make($request->all(), [
            'source' => [
                'required',
                Rule::in(self::getSources()->keys()),
            ],
            'file' => [
                'file',
                'required_if:source,file'
            ],
        ])->validate();

        $persons = [];
        $title = null;
        
        // Source: Helpers
        if ($request->source == 'helpers') {
            $persons = Helper::active()
                ->get()
                ->map(function($helper){ return self::helperToBadgePerson($helper); });
        }
        // Source: File
        else if ($request->source == 'file') {
            $file = $request->file('file');
            \Excel::selectSheets()->load($file, function($reader) use(&$persons) {
                $reader->each(function($sheet) use(&$persons) {
                    $sheet->each(function($row) use(&$persons) {
                        if (isset($row->name) && isset($row->position))
                        $persons[] = [
                            'name' => $row->name,
                            'position' => $row->position,
                            'code' => $row->code ?? null,
                        ];
                    });
                });
            });
        }

        // Ensure there are records
        Validator::make([], [])
            ->after(function ($validator) use($persons) {
                if (count($persons) == 0) {
                    $validator->errors()->add('source', __('app.empty_data_source'));
                }
            })
            ->validate();

        $data = collect($persons)->sortBy('name')->mapWithKeys(function($e){
            $id = md5(uniqid(null, true));
            return [ $id => $e ];
        });
        $request->session()->put(self::BADGE_ITEMS_SESSION_KEY, $data->toArray());

        return view('badges.selection', [
            'persons' => $data->map(function($e){ 
                return $e['name'] . ($e['position'] != null ? ' (' . $e['position'] . ')' : '');
            })->toArray(),
        ]);
    }

    public function make(Request $request) {
        $validator = Validator::make($request->all(), [
            'persons' => [
                'required',
                'array',
            ],
            'persons.*' => [
                'string'
            ],
            'alt_logo' => [
                'file',
                'image',
            ],
        ]);
        if ($validator->fails()) {
            return redirect()->route('badges.index')
                ->with('error', implode(', ', $validator->errors()->all()));
        }

        // Retrieve data
        $data = $request->session()->get(self::BADGE_ITEMS_SESSION_KEY, []);
        $persons = collect($request->persons)->map(function($e) use($data) { 
            $person = $data[$e];

            if (isset($person['type']) && $person['type'] == 'helper' && isset($person['id'])) {
                $helper = Helper::find($person['id']);
                if ($helper != null) {
                    $helper->person->staff_card_no = substr(bin2hex(random_bytes(16)), 0, 7);
                    $helper->person->save();
                    $person['code'] = $helper->person->staff_card_no;
                }
            }

            return $person;
        });

        // Ensure there are records
        if (count($persons) == 0) {
            return redirect()->route('badges.index')
                ->with('error', __('app.empty_data_source'));
        }    

        $title = __('people.badges');

        $badgeCreator = new BadgeCreator($persons);
        if ($request->hasFile('alt_logo')) {
            $badgeCreator->setLogo($request->file('alt_logo'));
        }
        $badgeCreator->createPdf($title);
    }

    private static function helperToBadgePerson($helper) {
        return [
            'type' => 'helper',
            'id' => $helper->id,
            'name' => $helper->person->nickname ?? $helper->person->name,
            'position' => is_array($helper->responsibilities) ? implode(', ', $helper->responsibilities) : '',
        ];
    }
}