<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Volunteer;
use App\VolunteerJob;
use App\VolunteerJobCategory;

class VolunteeringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $short = VolunteerJobCategory::create([
            'title' => [
                'en' => 'Short-term Volunteer (2-3 Weeks)',
                'de' => 'Short-term-VolontärIn (2 – 3 Wochen)'
            ],
            'order' => 0,
        ]);
        $mid = VolunteerJobCategory::create([
            'title' => [
                'en' => 'Mid-term Volunteer (3-8 Weeks)',
                'de' => 'Mid-term-VolontärIn (3 – 8 Wochen)'
            ],
            'order' => 1,
        ]);
        $long = VolunteerJobCategory::create([
            'title' => [
                'en' => 'Long-term Volunteer (more than two months)',
                'de' => 'Long-term-VolontärIn (mind. 2 Monate)'
            ],
            'order' => 2,
        ]);
        $project = VolunteerJobCategory::create([
            'title' => [
                'en' => 'Project Volunteer',
                'de' => 'Projekt-VolontärIn'
            ],
            'order' => 3,
        ]);

        $short->jobs()->create([
            'title' => [
                'en' => 'Short-term Volunteer',
                'de' => 'Short-term-Freiwillige/r'
            ],
            'description' => [
                'en' => 'A short-term volunteer trip is a great opportunity for anyone on a tight schedule. Even in your two to three weeks our community center will offer you a broad insight into humanitarian work. You will support our helpers and get to know their different projects such as the bank, the clothing boutique, the kids’ space and the café. For more information see our volunteer information sheet.',
                'de' => 'Dein Kalender ist schon voll, aber dein Herz schlägt für die Freiwilligenarbeit? In unserem Gemeinschaftszentrum hast du auch mit zwei oder drei Wochen die Möglichkeit, einen breiten Einblick in die humanitäre Arbeit zu erhalten. Als Kurzzeit-Freiwillige/r unterstützt du unsere Helpers und lernst die verschiedenen Projekte kennen wie die Bank, die Kleider-Boutique, das Kinderzimmer und das Café. Genauere Informationen findest du in unseren Freiwilligeninformationen!'
            ],
            'available_dates' => [
                'en' => 'year-round',
                'de' => 'jederzeit'
            ],
            'minimum_stay' => [
                'en' => '2 weeks',
                'de' => 'zwei Wochen'
            ],
            'requirements' => [
                'en' => 'at least 20 years old',
                'de' => 'mindestalter 20 Jahre'
            ],
            'order' => 0,
        ]);

        $long->jobs()->create([
            'title' => [
                'en' => 'Activity Coordinator',
                'de' => 'Aktivitäten-KoordinatorIn'
            ],
            'description' => [
                'en' => 'We always love to bring a sparkle to our visitors’ eyes with exciting activities in addition to our regular projects. Open Mic Parties, fashion shows or children’s face painting events – as our activity coordinator you will have one of the most creative tasks at the community center. You will develop and plan activities, staff the events and oversee the implementations. In close cooperation with our coordination team you will realise project ideas of our helpers and volunteers and enchant us with your own creative inputs. As an activity coordinator you will still have the opportunity to help us with our daily volunteer tasks.',
                'de' => 'Nichts macht uns so glücklich, wie durch verschiedenste Aktivitäten ein Funkeln in die Augen unserer BesucherInnen zu zaubern. Open-Mic-Partys, Fashion Shows oder Kinderschminken – als unser/e AktivitätenkoordinatorIn hast du eine der kreativsten Aufgaben in unserem Community Center. Du entwickelst und planst Aktivitäten, versorgst sie mit Freiwilligen und überschaust die Umsetzung. In enger Zusammenarbeit mit unserem Koordinationsteam setzt du die Projektideen unserer HelperInnen und Freiwilligen um und bezauberst uns mit deinen eigenen kreativen Ideen. Als Aktivitäten-KoordinatorIn arbeitest du natürlich auch in unseren regulären Projekten als Freiwillige/r mit.'
            ],
            'available_dates' => [
                'en' => 'from June 2018',
                'de' => 'ab Juni 2018 jederzeit'
            ],
            'minimum_stay' => [
                'en' => '2 months',
                'de' => 'zwei Monate'
            ],
            'requirements' => [
                'en' => 'at least 20 years old, creative, experienced in working with people',
                'de' => 'Mindestalter 20 Jahre, kreativ, Erfahrung in der Arbeit mit Menschen'
            ],
            'order' => 0,
        ]);
        $long->jobs()->create([
            'title' => [
                'en' => 'Adult School Coordinator',
                'de' => 'KoordinatorIn Erwachsenenbildung'
            ],
            'description' => [
                'en' => 'You live as many lives as the languages you know, a proverb says. To pave our helpers’ and visitors’ way for the future, we offer language classes at our education center for adults. Our teachers come from the communities in the camps and teach Arabic, Farsi and French. Also, we invite all our international volunteers to participate in the English conversation classes for students who would like to expand their vocabulary. As our adult school coordinator, you will make sure that the teachers’ positions are filled at all times and find replacements if needed. You will be responsible for new students’ registrations and available material like chalk, pens and notebooks. Since in our eyes networking is important, you will not only regularly meet with the teachers, but also with other actors providing education to the people in the camps. And, last but not least, you can also teach!',
                'de' => 'Man lebt so viele Leben wie die Sprachen, die man spricht, sagt ein Sprichwort. Um unseren Helpers und BesucherInnen den Weg für die Zukunft zu ebnen, bieten wir in unserem Zentrum für Erwachsenenbildung Sprachunterricht an. Unsere LehrerInnen kommen aus den verschiedenen Communitys in den Camps und unterrichten Arabisch, Farsi und Französisch. Wir laden außerdem alle Freiwilligen ein, an den täglichen Konversationskursen in Englisch teilzunehmen und unseren SchülerInnen spielerisch zu helfen, ihren Wortschatz zu erweitern. Als KoordinatorIn des Zentrums stellst du sicher, dass alle LehrerInnenstellen besetzt sind und findest NachfolgerInnen. Du bist verantwortlich für die Registrierung neuer SchülerInnen und überprüfst unseren Bedarf an Schulmaterial wie Kreide, Stifte und Hefte. Da uns die Vernetzung ausgesprochen wichtig ist, lädst du nicht nur zu regelmäßigen Koordinationstreffen mit den LehrerInnen ein, sondern auch mit anderen Akteuren auf der Insel, die Erwachsenenbildung für Menschen aus den Camps anbieten. Last but not least – wir freuen uns natürlich riesig, wenn du auch selber unterrichten möchtest! Als Erwachsenenbildung-KoordinatorIn arbeitest du natürlich auch in unseren regulären Projekten als Freiwillige/r mit.'
            ],
            'available_dates' => [
                'en' => 'from July 2018',
                'de' => 'ab Mitte Juli jederzeit'
            ],
            'minimum_stay' => [
                'en' => '2 months',
                'de' => 'zwei Monate'
            ],
            'requirements' => [
                'en' => 'at least 20 years old, experienced in working with people and in an educational setting',
                'de' => 'Mindestalter 20 Jahre, Erfahrung in der Arbeit mit Menschen im Bildungsumfeld'
            ],
            'order' => 1,
        ]);
        $long->jobs()->create([
            'title' => [
                'en' => 'Adult School Coordinator',
                'de' => 'Shift Coordinator'
            ],
            'description' => [
                'en' => 'You live as many lives as the languages you know, a proverb says. To pave our helpers’ and visitors’ way for the future, we offer language classes at our education center for adults. Our teachers come from the communities in the camps and teach Arabic, Farsi and French. Also, we invite all our international volunteers to participate in the English conversation classes for students who would like to expand their vocabulary. As our adult school coordinator, you will make sure that the teachers’ positions are filled at all times and find replacements if needed. You will be responsible for new students’ registrations and available material like chalk, pens and notebooks. Since in our eyes networking is important, you will not only regularly meet with the teachers, but also with other actors providing education to the people in the camps. And, last but not least, you can also teach!',
                'de' => 'Every ship needs a captain and every shift a shift coordinator! As our shift coordinator you sit together with our volunteers at the beginning of every shift and arrange all tasks of the day. During your shift you supervise the volunteers, answer all questions related to their projects and check the wind direction on a regular basis, so our ship will always be on the right course. As a shift coordinator you will still have the opportunity to help us with our daily volunteer tasks.'
            ],
            'available_dates' => [
                'en' => 'from May 2018',
                'de' => 'ab Mai 2018 jederzeit'
            ],
            'minimum_stay' => [
                'en' => '2 months',
                'de' => 'zwei Monate'
            ],
            'requirements' => [
                'en' => 'at least 20 years old, well-organised, experienced in working with children',
                'de' => 'Mindestalter 20 Jahre, organisiert, Erfahrung in der Arbeit mit Kindern'
            ],
            'order' => 2,
        ]);
        
    }
}
