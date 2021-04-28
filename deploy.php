<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'Addictives');

// Project repository
set('repository', 'git@appsolute-git.fr:addictives/addictives-web');
set('http_user', 'www-data');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

// Hosts

host('develop')
    ->set('branch', 'develop')
    ->user('appsolute')
    ->hostname('addictives.appsolute.dev')
    ->set('deploy_path', '/var/www/addictives.appsolute.dev')
    ->forwardAgent(true)
    ->multiplexing(false)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no');

host('staging')
    ->set('branch', 'staging')
    ->user('appsolute')
    ->hostname('addictives-preprod.apps-dev.io')
    ->set('deploy_path', '/var/www/addictives-preprod.apps-dev.io')
    ->forwardAgent(true)
    ->multiplexing(false)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});


// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

task('reload:php-fpm', function () {
    run('sudo /usr/sbin/service php7.4-fpm reload');
});

task('database:backup', function () {
    run('{{bin/php}} {{release_path}}/artisan backup:run');
})->onHosts('preprod', 'prod', 'preprod_us', 'prod_us');

after('deploy', 'reload:php-fpm');
after('deploy', 'database:backup');
after('rollback', 'reload:php-fpm');
