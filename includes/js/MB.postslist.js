/**
 * Listing posts
 */
var MB = (MB || {});

MB.CONST.Posts = {
    FEATURE_LINK: MB.CONST.SiteSettings.ADMIN_DOCUMENT_ROOT + "post/feature.php",
    PUBLISH_LINK: MB.CONST.SiteSettings.ADMIN_DOCUMENT_ROOT + "post/publish.php",
    REMOVE_LINK: MB.CONST.SiteSettings.ADMIN_DOCUMENT_ROOT + "post/remove.php"
};

function PostListViewModel() {
    var self = this;

    self.posts = ko.observableArray();
    self.loaded = ko.observable(false);
    self.categoryMap = ko.observableArray();
    self.categoriesMapped = ko.observable(false);
    self.postsLoaded = ko.observable(false);
    self.featuredPostId = ko.observable();

    self.initData = function() {
        $.getJSON("../adm/post/", function(allData) {
            var mappedPosts = ko.utils.arrayMap(allData, function(item) {
                return new PostModel(
                    item.post_id,
                    item.post_slug,
                    item.post_title,
                    item.post_content,
                    item.post_category,
                    item.post_category_name,
                    item.date,
                    item.published,
                    item.featured,
                    self.categoryMap()
                );
            });
            self.posts(mappedPosts);

            self.postsLoaded(true);
        });
    };

    self.getCategoryMappings = function() {
        $.getJSON("../adm/category/map.php", function(categories) {
            var mappedCategories = new Array();
            ko.utils.arrayForEach(categories, function(category) {
                mappedCategories[category.cat_id] = category.name;
            });

            self.categoryMap(mappedCategories);

            self.initData();
        });
    };

    // Set the initial featured post
    self.setInitialFeaturedPost = function() {
        $.getJSON(MB.CONST.Posts.FEATURE_LINK, function(post) {
            // Should just be one post id
            if(post.featured !== undefined) {
                self.featuredPostId(post.featured);
            }
        });
    };

    /**
     * publishPost will "flip the bit" of the published status, so if it is published,
     *     it will be unpublished, and vice versa.
     */
    self.publishPost = function(post) {
        var data = {
            postid: post.postId,
            published: (post.published() ? 0 : 1)
        };
        $.post(MB.CONST.Posts.PUBLISH_LINK, data, function(returnedData) {
            post.published(returnedData.published);
        });
    };

    self.deletePost = function(post) {
        if(confirm("Are you sure you want to delete the post " + post.postTitle + "?")) {
            var data = {
                postid: post.postId
            };
            $.post(MB.CONST.Posts.REMOVE_LINK, data, function(returnedData) {
                self.posts.remove(post);
            });
        }
    };

    self.getCategoryMappings();

    self.setInitialFeaturedPost();

    self.featuredPostId.subscribe(function(newValue) {
        var data = {
            postid: newValue
        }
        $.post(MB.CONST.Posts.FEATURE_LINK, data, function(returnedData) {
            self.featuredPostId(returnedData.featured);
        });
    });
}
