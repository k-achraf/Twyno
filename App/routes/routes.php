<?php

use System\Application;
$app = Application::getInstance();

/***************************************************/

/**** Admin Routes ****/
// login
$app->route->add('/admin/login' , 'Admin/Auth');
$app->route->add('/admin/login' , 'Admin/Auth@login' , 'POST');
$app->route->add('/admin/logout' , 'Admin/Auth@logout');

//Dashboard
$app->route->add('/admin' , 'Admin/Admin');
$app->route->add('/admin/dashboard' , 'Admin/Admin');

//admin => users
$app->route->add('/admin/users' , 'Admin/Users');
$app->route->add('/admin/users/add' , 'Admin/Users@add' , 'POST');
$app->route->add('/admin/users/:id/edit' , 'Admin/Users@edit');
$app->route->add('/admin/users/:id/edit' , 'Admin/Users@save' , 'POST');
$app->route->add('/admin/users/:id/delete' , 'Admin/Users@delete');

//admin => posts
$app->route->add('/admin/posts' , 'Admin/Posts');
$app->route->add('/admin/posts/add' , 'Admin/Posts@add' , 'POST');
$app->route->add('/admin/posts/:id/edit' , 'Admin/Posts@edit');
$app->route->add('/admin/posts/:id/edit' , 'Admin/Posts@save' , 'POST');
$app->route->add('/admin/posts/:id/delete' , 'Admin/Posts@delete');

//admin => comments

$app->route->add('/admin/posts/:id/comments' , 'Admin/Comments');
$app->route->add('/admin/comments/:id/edit' , 'Admin/Comments@edit');
$app->route->add('/admin/comments/:id/edit' , 'Admin/Comments@save' , 'POST');
$app->route->add('/admin/comments/:id/delete' , 'Admin/Comments@delete');

//admin => categories
$app->route->add('/admin/categories' , 'Admin/Categories');
$app->route->add('/admin/categories/add' , 'Admin/Categories@add' , 'POST');
$app->route->add('/admin/categories/:id/edit' , 'Admin/Categories@edit');
$app->route->add('/admin/categories/:id/edit' , 'Admin/Categories@save' , 'POST');
$app->route->add('/admin/categories/:id/delete' , 'Admin/Categories@delete');

//admin => users-groups
$app->route->add('/admin/users-groups' , 'Admin/UsersGroups');
$app->route->add('/admin/users-groups/add' , 'Admin/UsersGroups@add' , 'POST');
$app->route->add('/admin/users-groups/:id/edit' , 'Admin/UsersGroups@edit');
$app->route->add('/admin/users-groups/:id/edit' , 'Admin/UsersGroups@save' , 'POST');
$app->route->add('/admin/users-groups/:id/delete' , 'Admin/UsersGroups@delete');

//admin => ads
$app->route->add('/admin/ads' , 'Admin/Ads');
$app->route->add('/admin/ads/add' , 'Admin/Ads@add' , 'POST');
$app->route->add('/admin/ads/:id/edit' , 'Admin/Ads@edit');
$app->route->add('/admin/ads/:id/edit' , 'Admin/Ads@save' , 'POST');
$app->route->add('/admin/ads/:id/delete' , 'Admin/Ads@delete');

//admin => settings
$app->route->add('/admin/settings' , 'Admin/Settings');
$app->route->add('/admin/settings/save' , 'Admin/Settings@save' , 'POST');

//admin => contacts
$app->route->add('/admin/contacts' , 'Admin/Contacts');
$app->route->add('/admin/Contacts/:id/reply' , 'Admin/Contacts@reply');
$app->route->add('/admin/Contacts/:id/reply' , 'Admin/Contacts@send' , 'POST');
/***************************************************/
$app->route->add('/404' , 'NotFound');
$app->route->notFound('/404');