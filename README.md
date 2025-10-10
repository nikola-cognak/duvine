WordPress Starter Kit
=====================

A starter kit for WordPress builds from scratch. Includes WordPress core and a custom, completely barebones theme. As in, absolutely no styling and a very basic structure.

Theme based on [_s](https://github.com/Automattic/_s/).


# Important Development notes

There is a bug with the **Simple Custom Post Order** plugin and ajax requests when it comes to sorting query results. Basically, WordPress sends all ajax requests (even from the front end) as admin, so when we're ajaxing the tour results and specifying an order, the plugin suppresses all orderby parameters in the query, based on how they're treating queries on the admin side.

Unfortunately, this seems to be a side effect of all the custom post ordering plugins. WTF guys.

So if the **Simple Custom Post Order** plugin comes up for an update (and the update doesn't address this), you'll want to do a small thing to the function they've added to the `pre_get_posts` filter (as of this time, that function is `scporder_pre_get_posts`).

Find the `if` statement checking for `is_admin()` and add the following check for ajax:

```
!(defined('DOING_AJAX') && DOING_AJAX)
```

As of this past version of the plugin, the code looked like the following:

```
    function scporder_pre_get_posts($wp_query) {
        $objects = $this->get_scporder_options_objects();
        if (empty($objects))
            return false;


        // replace this
        //if( is_admin() ){

        // with this
        if ( !(defined('DOING_AJAX') && DOING_AJAX) && is_admin()) {

            if (isset($wp_query->query['post_type']) && !isset($_GET['orderby'])) {
                if (in_array($wp_query->query['post_type'], $objects)) {
                    $wp_query->set('orderby', 'menu_order');
                    $wp_query->set('order', 'ASC');
                }
            }
        } else {

            ...

```



# Depolying
The DuVine brand site is set up ON WP Engine. To set up to pull/push from the WP Engine installation, you'll need to first add yourself as a developer under the `Git push` menu in WP Engine by adding your SSH public key. Once that is set up, you can add the following remotes to hook into production or staging:

**Staging**
git@git.wpengine.com:staging/duvine.git

**Production**
git@git.wpengine.com:production/duvine.git

You can add a remote by using something like the following (using staging as an example):
`git remote add wpengine-staging git@git.wpengine.com:staging/duvine.git`

And then pushing to the staging environment by running the following (assuming you want to push up the `master` branch):
`git push wpengine-staging master`



Setting up this project
-----------------------
1. Clone this repo.
1. Run `npm install` to install gulp and get all that good stuff set up.
1. Install the database, from wherever you can get it. Preferrably, log onto production or staging and to a WP Migrate DB dump.
1. Create `wp-config.php` by copying the `wp-sample-config.php` and add the appropriate database credentials.


Namespacing
-----------
Functions are namespaced with the prefix `sk`.



Gulp Tasks
----------

Gulp is used to maintain and complete a number of tasks for the site, including compiling sass, optimizing svgs, and more. You can use `gulp help` to get an overview of the tasks present in this project, or use the reference below.

#### `gulp`
Runs the default task, which is nothing at the moment.

#### `gulp help`
List all the tasks defined in the gulpfile, and see a description of what they do.

#### `gulp combine`
Concatenates all the javascripts from the arsenal, any plugin scripts, and the main js file

#### `gulp opt-js`
Optimizes javascript by concatenating all the enabled arsenal scripts, the plugins, and the main js file, then minifying that file.

#### `gulp svgstore`
Creates the svg sprite for insertion into the page.

#### `gulp styles`
Compiles sass.

#### `gulp watch`
Watch the `css` directory within the theme to changes to any `.scss` files.

#### `gulp build-arsenal`
Using the `setup.json` file, populates the theme with the chosen build components, including javascript modules and WordPress content types.




Custom Page Blocks
------------------
This theme uses Advanced Custom Fields to create a Flexible Content field for the "page" content type that allows the user to add additional content blocks onto that page. To set this functionality up:

1. Enable the `Advanced Custom Fields Pro` plugin.
1. Import the `acf-page-blocks.json` file in the `_imports` directory.

Once you import the json file you will find a "Page Blocks" field group in the ACF admin section. This will include a Flexible Content field called `Page Blocks` that will start off with one layout, the "Simple Copy Block."

To add a new block, duplicate the `block.php` file inside the `blocks` directory in the theme, and rename the copied file to reflect the function of the block (include a leading `block-` in the filename). Back in the ACF admin and the "Page Blocks" field group, add a new layout and add the relevant fields for that layout. The "name" field for this layout should match the php file name (*without* the trailing `.php`). For example, if you created a block called `block-latest_blog_posts.php`, you would name the layout `latest_blog_posts`.

When you add a new layout, this block will be available in the "Page Blocks" field group on all pages.




Going Live
----------

In production, set the environment variable WP_ENV variable in apache to "production" to enqueue the production scripts and styles.

```sh
SetEnv WP_ENV "production"
```



API Reference
-------------

### Getting fields

####**sk\_the\_field( $field, $args = array() )**
Helper function that enhances the ACF `the_field` function with some optional settings.

`$field` is the string indicating the ACF field to get.

`$args` is an associative array with the following options:

* `id` (int) : Post we're getting the field for.
* `before` (string) : HTML markup to appear before the field.
* `after` (string) : HTML markup to appear after the field.
* `filter` (string) : Any filter you'd like to apply to the field.
* `filter_args` (array) : An associative array that will pass the values to the given filter.
* `sub_field` (boolean) : Whether or not this field is a sub-field. Default is FALSE.
* `default` (mixed) : If the field is undefined, the default value of this function call.
* `return` (boolean) : If true, return the value of this field rather than echo it out. Default is FALSE
* `debug` (boolean) : Debug flag; offers the option to print out helpful debugging data.

If the `return` argument is true, this function returns a string containing the markup with the requested field.


####**sk\_get\_field( $field, $args = array() )**
Wrapper to call the `sk_the_field()` function with the `return` parameter set to TRUE.


####**sk\_the\_subfield( $field, $args = array() )**
Wrapper to call the `sk_the_field()` function with the `sub_field` parameter set to TRUE.


####**sk\_get\_subfield( $field, $args = array() )**
Wrapper to call the `sk_the_field()` function with the `sub_field` and `return` parameters set to TRUE.


####**sk\_block\_field( $field, $args = array() )**
Wrapper to display a field within a block. Since the page blocks utilizes an ACF repeater field, this is an alias of `sk_the_subfield()`.



### Rendering

####**sk\_the\_page\_blocks()**
Hooks into the ACF repeater field to render all the additional page blocks for a page. Calls tempaltes in the blocks/ directory





Theme Filters
-------------

There are a number of filters that can be used to handle values within the theme. The source for these filters can be found in `inc/filters.php`.


####**sk\_image\_markup**
Filter for handling an image object from the database and returning a valid img tag with the appropriate src from that image object. This filter can be passed an associative array with the following values:

* `img_size` (string) : The registered image size in WordPress


####**sk\_link\_email**
Renders an email address as a linked link.


####**sk\_sanitize\_svg**
Sanitize any values coming through the CMS that should be output as HTML and make sure that it's svg code.


####**sk\_youtube\_video\_embed**
Renders markup for a youtube video embed from a YouTube video ID.




Admin Filters
-------------

Within the theme directory, there are some admin filters in place in the `inc/admin.php` file that extend the administrative functionality. Check out that file to add WYSIWYG styles, add columns for custim fields to post types, adjust the administrative interface, and more.


Run project from docker
-------------

Pre-requirements:
* installed `docker`
* installed `docker-compose`
* properly configured access rights for docker (do not run as `sudo`)

Steps to run:
1. Clone via `git clone` command project into `{PROJECT_DIR}` path
2. Check and fix (if necessary) access rights and owner for `{PROJECT_DIR}` (not `sudo`)
3. Get db dump
4. In table `wp_options` change field `option_value` to `http://duvine.loc` for record with field `option_name = 'siteurl' AND option_name = 'home'`
5. Paste dump file in directory `{PROJECT_DIR}/docker/database/sql-scripts/` with name `duvine.sql`
6. Copy file `wp-config-sample.php` and paste as `wp-config.php` (credentials for database are already here, don't change them)
7. Check ports `80` and `9000` usage, if it's in use release the port (`80 `can be used for `nginx/apache`, `9000` can be used for `php-fpm`)
8. Run command `docker-compose build`
9. Run command `docker-compose up`

Useful commands:
* to stop all containers - `docker-compose stop`
* to stop and remove all containers - `docker-compose down` (will keep data from build)
* to clear all data - `docker system prune -af --volumes`
* to show all images - `docker-compose ps`