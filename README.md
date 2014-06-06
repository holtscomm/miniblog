HoltsComm Miniblog
==================

## Requirements

1. PHP 5+
2. Access to a MySQL database

## Installation Instructions

Note: Settings for the database are found in `includes/config.php`, and should be changed there, depending on your configuration.

### Create a database

The default database name is `miniblog`, but feel free to call it whatever you want to, or need to based on your service provider (some providers require a prefix of some type).

### Run the install.php script

Navigate to `install.php` in your browser.

- If you are running these files locally, make sure your servers are started!

Click through the steps of `install.php` and resolve any issues that come up - they will be printed in the "Mysql said" box on each page.

### Navigate to /adm/ and login

The default password for the admin area is `password`. It is recommended that you change your password immediately using the "Change password" link in the admin area header.

### Modify the post template to your needs

The templating "engine" exposes some variables that you can use in your templates. Here is the default template, which uses all of the available variables:

```html
<div class="post" id="post-$postid$">
    <h4><a href="$posturl$">$posttitle$</a></h4>
    <span class="date">$postdate$</span>
    <div class="post-content">
        $postcontent$
    </div>
    <a href="?category=$postcategoryname$">View more posts from this category</a>
</div>
```

#### Template variables and their contents

- `$postid$` - The id of the post in the database
- `$posturl$` - The relative url of the post, i.e. `index.php?post=example`
- `$posttitle$` - The title of the post
- `$postdate$` - The date the post was created - will not change
- `$postcontent$` - The HTML content of the post
- `$postcategoryname$` - The category this post is in. Can be blank.

The power of the template comes in the fact that it is very simple to modify to conform with your existing CSS or other styling.

### Printing posts to a page

To get at the list of posts, you should have the code at the top of the page you want the posts to appear on. This is commonly just index.php.

```php
<?php
define('IN_BLOG', true);
define('PATH', '');
include('includes/miniblog.php');
?>
```

Including that code will expose a few variables for you to use, and that can be seen in effect on the current `index.php` file. Here are the list of variables and their contents:

- `$miniblog_posts` - All of the posts in the database, in the form of your `template.html`. Echoing this variable to the page will print out all of your posts (defaulting to 5)
- `$single` - Boolean - true if the user is viewing a single post
- `$miniblog_previous` - String - Will contain the link to the list of posts that are earlier than the ones currently displayed
- `$miniblog_next` - String - Will contain the link to the list of posts that are later than the ones currently displayed
- `$config['miniblog-filename']` - The name of the file being used for the base of the posts. Defined in the admin area under Options
- `$category_link` - Relative category URL to the current page, e.g. `?category=news`
