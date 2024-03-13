{{ ($admin->unreadNotifications->count() > 99) ? '99+' : $admin->unreadNotifications->count() }}

