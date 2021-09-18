[![Build Status](https://travis-ci.com/eKristensen/pop-self-service.svg?branch=master)](https://travis-ci.com/eKristensen/pop-self-service)
[![codecov](https://codecov.io/gh/eKristensen/pop-self-service/branch/master/graph/badge.svg)](https://codecov.io/gh/eKristensen/pop-self-service)
[![StyleCI](https://github.styleci.io/repos/145050174/shield?branch=master)](https://github.styleci.io/repos/145050174)
[![Dependabot Status](https://api.dependabot.com/badges/status?host=github&repo=eKristensen/pop-self-service)](https://dependabot.com)

# pop-self-service
POP self service system. Only function right now is to reset password for K-net and booking. Following best practices is important: https://postmarkapp.com/guides/password-reset-email-best-practices

# List of tests:

-> API, Open all URLS, GET, PATCH, POST with and without data, correct error messages.

# Codecov.io

Token is saved in travis.ci as an hidden environment variable

## Laravel scheduler run

https://laravel.com/docs/8.x/scheduling#running-the-scheduler

Run as www-data or similar, NOT ROOT! Edit via e.g. <code>sudo crontab -u www-data -e</code> (if user is www-data)

    * * * * * cd [Web application patch] && php artisan schedule:run >> /dev/null 2>&1

# SELinux

    # semanage fcontext -a -t httpd_sys_rw_content_t "/knetsystem/reset-password/storage/framework/views(/.*)?"
    # semanage fcontext -a -t httpd_sys_rw_content_t "/knetsystem/reset-password/storage/framework/sessions(/.*)?"
    # semanage fcontext -a -t httpd_sys_rw_content_t "/knetsystem/reset-password/storage/framework/cache(/.*)?"
    # semanage fcontext -a -t httpd_sys_content_t "/knetsystem/reset-password(/.*)?"
    # restorecon -R -v /knetsystem/reset-password/public/

Check it out here: /etc/selinux/targeted/contexts/files/file_contexts.local, should be like this

    /knetsystem/reset-password/storage/framework/views(/.*)?    system_u:object_r:httpd_sys_rw_content_t:s0
    /knetsystem/reset-password/storage/framework/sessions(/.*)?    system_u:object_r:httpd_sys_rw_content_t:s0
    /knetsystem/reset-password/storage/framework/cache(/.*)?    system_u:object_r:httpd_sys_rw_content_t:s0
    /knetsystem/reset-password/public(/.*)?    system_u:object_r:httpd_sys_content_t:s0

# Service file for qorker

    sudo systemctl edit --force --full reset-password.service

    [Unit]
    Description=K-Net Reset Password - Runs and keeps alive the artisan queue:work process
    After=network.target

    [Service]
    User=apache
    Group=apache
    Restart=always
    WorkingDirectory=/knetsystem/reset-password
    ExecStart=/usr/bin/php artisan queue:work database

    [Install]
    WantedBy=multi-user.target

    sudo systemctl enable --now reset-password.service
