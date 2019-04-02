<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextMenus, RegisterContextButtons;

    protected $navigationItems = [
        \App\Navigation\Drawer\HomeNavigationItem::class => 0,
        \App\Navigation\Drawer\PeopleNavigationItem::class => 1,
        \App\Navigation\Drawer\BankNavigationItem::class => 2,
        \App\Navigation\Drawer\HelpersNavigationItem::class => 3,
        \App\Navigation\Drawer\ShopNavigationItem::class => 8,
        \App\Navigation\Drawer\BarberNavigationItem::class => 9,
        \App\Navigation\Drawer\LibraryNavigationItem::class => 10,
        \App\Navigation\Drawer\BadgesNavigationItem::class => 13,
        \App\Navigation\Drawer\ReportingNavigationItem::class => 14,
        \App\Navigation\Drawer\UsersNavigationItem::class => 15,
        \App\Navigation\Drawer\LogViewerNavigationItem::class => 16,
    ];

    protected $contextMenus = [
        'people.index' => \App\Navigation\ContextMenu\PeopleContextMenu::class,
        'bank.withdrawal' => \App\Navigation\ContextMenu\BankWithdrawalContextMenu::class,
        'bank.withdrawalSearch' => \App\Navigation\ContextMenu\BankWithdrawalContextMenu::class,
        'people.helpers.index' => \App\Navigation\ContextMenu\HelpersContextMenu::class,
    ];

    protected $contextButtons = [
        'changelog' => \App\Navigation\ContextButtons\ChangelogContextButtons::class,

        'userprofile.view2FA' => \App\Navigation\ContextButtons\UserProfile2FAContextButtons::class,

        'users.index' => \App\Navigation\ContextButtons\UserIndexContextButtons::class,
        'users.create' => \App\Navigation\ContextButtons\UserCreateContextButtons::class,
        'users.show' => \App\Navigation\ContextButtons\UserShowContextButtons::class,
        'users.edit' => \App\Navigation\ContextButtons\UserEditContextButtons::class,
        'users.permissions' => \App\Navigation\ContextButtons\UserPermissionsContextButtons::class,

        'roles.index' => \App\Navigation\ContextButtons\RoleIndexContextButtons::class,
        'roles.create' => \App\Navigation\ContextButtons\RoleCreateContextButtons::class,
        'roles.show' => \App\Navigation\ContextButtons\RoleShowContextButtons::class,
        'roles.edit' => \App\Navigation\ContextButtons\RoleEditContextButtons::class,
        'roles.permissions' => \App\Navigation\ContextButtons\RolePermissionsContextButtons::class,

        'people.index' => \App\Navigation\ContextButtons\PeopleIndexContextButtons::class,
        'people.create' => \App\Navigation\ContextButtons\PeopleCreateContextButtons::class,
        'people.show' => \App\Navigation\ContextButtons\PeopleShowContextButtons::class,
        'people.relations' => \App\Navigation\ContextButtons\PeopleRelationsContextButtons::class,
        'people.edit' => \App\Navigation\ContextButtons\PeopleEditContextButtons::class,
        'people.duplicates' => \App\Navigation\ContextButtons\PeopleDuplicatesContextButtons::class,
        'people.import' => \App\Navigation\ContextButtons\PeopleImportContextButtons::class,
        
        'people.helpers.index' => \App\Navigation\ContextButtons\HelperIndexContextButtons::class,
        'people.helpers.show' => \App\Navigation\ContextButtons\HelperShowContextButtons::class,
        'people.helpers.edit' => \App\Navigation\ContextButtons\HelpersEditContextButtons::class,
        'people.helpers.create' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.createFrom' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.import' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.export' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.report' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        
        'bank.withdrawal' => \App\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.withdrawalSearch' => \App\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.showCard' => \App\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.deposit' => \App\Navigation\ContextButtons\BankDepositContextButtons::class,
        'bank.prepareCodeCard' => \App\Navigation\ContextButtons\BankCodeCardContextButtons::class,
        'bank.settings.edit' => \App\Navigation\ContextButtons\BankSettingsContextButtons::class,
        'bank.withdrawalTransactions' => \App\Navigation\ContextButtons\BankWithdrawalTransactionsContextButtons::class,
        'bank.depositTransactions' => \App\Navigation\ContextButtons\BankDepositTransactionsContextButtons::class,
        'bank.maintenance' => \App\Navigation\ContextButtons\BankMaintenanceContextButtons::class,
        'bank.export' => \App\Navigation\ContextButtons\BankExportContextButtons::class,

        'coupons.index' => \App\Navigation\ContextButtons\CouponIndexContextButtons::class,
        'coupons.create' => \App\Navigation\ContextButtons\CouponCreateContextButtons::class,
        'coupons.show' => \App\Navigation\ContextButtons\CouponShowContextButtons::class,
        'coupons.edit' => \App\Navigation\ContextButtons\CouponEditContextButtons::class,
        
        'shop.index' => \App\Navigation\ContextButtons\ShopContextButtons::class,
        'shop.settings.edit' => \App\Navigation\ContextButtons\ShopSettingsContextButtons::class,

        'shop.barber.index' => \App\Navigation\ContextButtons\BarberContextButtons::class,
        'shop.barber.settings.edit' => \App\Navigation\ContextButtons\BarberSettingsContextButtons::class,
        
        'library.lending.index' => \App\Navigation\ContextButtons\LibraryLendingIndexContextButtons::class,
        'library.settings.edit' => \App\Navigation\ContextButtons\LibrarySettingsContextButtons::class,
        'library.lending.persons' => \App\Navigation\ContextButtons\LibraryReturnToIndexContextButtons::class,
        'library.lending.books' => \App\Navigation\ContextButtons\LibraryReturnToIndexContextButtons::class,
        'library.lending.person' => \App\Navigation\ContextButtons\LibraryLendingPersonContextButtons::class,
        'library.lending.personLog' => \App\Navigation\ContextButtons\LibraryLendingPersonLogContextButtons::class,
        'library.lending.book' => \App\Navigation\ContextButtons\LibraryLendingBookContextButtons::class,
        'library.lending.bookLog' => \App\Navigation\ContextButtons\LibraryLendingBookLogContextButtons::class,
        'library.books.index' => \App\Navigation\ContextButtons\LibraryBookIndexContextButtons::class,
        'library.books.create' => \App\Navigation\ContextButtons\LibraryBookCreateContextButtons::class,
        'library.books.edit' => \App\Navigation\ContextButtons\LibraryBookEditContextButtons::class,

        'badges.selection' => \App\Navigation\ContextButtons\BadgeSelectionContextButtons::class,
        
        'reporting.monthly-summary' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.people' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.withdrawals' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.deposits' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.privacy' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNavigationItems();
        $this->registerContextMenus();
        $this->registerContextButtons();
    }
}
