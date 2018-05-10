<?php

return [

    'backup' => [

        /*
         * The name of this application. You can use this name to monitor
         * the backups.
         */
        'name' => config('app.name'),

        'source' => [

            'files' => [

                /*
                 * The list of directories and files that will be included in the backup.
                 * 将包含在备份中的目录和文件列表
                 */
                'include' => [
                    base_path(),
                ],

                /*
                 * These directories and files will be excluded from the backup.
                 * 这些目录和文件将被排除在备份之外
                 *
                 * Directories used by the backup process will automatically be excluded.
                 * 备份过程使用的目录将自动排除
                 */
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                    // 那些目录不保存
                    // storage_path(),
                ],

                /*
                 * Determines if symlinks should be followed.
                 */
                'followLinks' => false,
            ],

            /*
             * The names of the connections to the databases that should be backed up
             * MySQL, PostgreSQL, SQLite and Mongo databases are supported.
             * 支持MySQL、PostgreSQL、SQLite和Mongo数据库的数据库连接的名称
             */
            'databases' => [
                // 要备份的库名 `config/database.php`
                'mysql',
            ],
        ],

        /*
         * The database dump can be gzipped to decrease diskspace usage.
         * 可以对数据库转储进行压缩，以减少磁盘空间使用
         */
        'gzip_database_dump' => false,

        'destination' => [

            /*
             * The filename prefix used for the backup zip file.
             * 用于备份zip文件的文件名前缀
             */
            'filename_prefix' => '',

            /*
             * The disk names on which the backups will be stored.
             * 将存储备份的磁盘名
             */
            'disks' => [
                // 'local',
                // 保存到那个目录 `config/filesystems.php`
                'backup',
            ],
        ],
    ],

    /*
     * You can get notified when specific events occur. Out of the box you can use 'mail' and 'slack'.
     * For Slack you need to install guzzlehttp/guzzle.
     * 当发生特定事件时，您可以得到通知。
     *
     * You can also use your own notification classes, just make sure the class is named after one of
     * the `Spatie\Backup\Events` classes.
     */
    'notifications' => [

        'notifications' => [
            \Spatie\Backup\Notifications\Notifications\BackupHasFailed::class         => ['mail'],
            \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFound::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupHasFailed::class        => ['mail'],
            \Spatie\Backup\Notifications\Notifications\BackupWasSuccessful::class     => ['mail'],
            \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFound::class   => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessful::class    => ['mail'],
        ],

        /*
         * Here you can specify the notifiable to which the notifications should be sent. The default
         * notifiable will use the variables specified in this config file.
         */
        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

        'mail' => [
            // 收件人
            'to' => 'q408596288@vip.qq.com',
        ],

        'slack' => [
            'webhook_url' => '',

            /*
             * If this is set to null the default channel of the webhook will be used.
             */
            'channel' => null,

            'username' => null,

            'icon' => null,

        ],
    ],

    /*
     * Here you can specify which backups should be monitored.
     * If a backup does not meet the specified requirements the
     * UnHealthyBackupWasFound event will be fired.
     */
    'monitorBackups' => [
        [
            'name' => config('app.name'),
            'disks' => ['local'],
            'newestBackupsShouldNotBeOlderThanDays' => 1,
            'storageUsedMayNotBeHigherThanMegabytes' => 5000,
        ],

        /*
        [
            'name' => 'name of the second app',
            'disks' => ['local', 's3'],
            'newestBackupsShouldNotBeOlderThanDays' => 1,
            'storageUsedMayNotBeHigherThanMegabytes' => 5000,
        ],
        */
    ],

    'cleanup' => [
        /*
         * The strategy that will be used to cleanup old backups. The default strategy
         * will keep all backups for a certain amount of days. After that period only
         * a daily backup will be kept. After that period only weekly backups will
         * be kept and so on.
         *
         * No matter how you configure it the default strategy will never
         * delete the newest backup.
         */
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

        'defaultStrategy' => [

            /*
             * The number of days for which backups must be kept.
             */
            'keepAllBackupsForDays' => 7,

            /*
             * The number of days for which daily backups must be kept.
             */
            'keepDailyBackupsForDays' => 16,

            /*
             * The number of weeks for which one weekly backup must be kept.
             */
            'keepWeeklyBackupsForWeeks' => 8,

            /*
             * The number of months for which one monthly backup must be kept.
             */
            'keepMonthlyBackupsForMonths' => 4,

            /*
             * The number of years for which one yearly backup must be kept.
             */
            'keepYearlyBackupsForYears' => 2,

            /*
             * After cleaning up the backups remove the oldest backup until
             * this amount of megabytes has been reached.
             */
            'deleteOldestBackupsWhenUsingMoreMegabytesThan' => 5000,
        ],
    ],
];
