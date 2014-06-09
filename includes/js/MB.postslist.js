/**
 * Listing posts
 */
var MB = (MB || {});

MB.CONST.Posts = {
    FEATURE_LINK: MB.CONST.SiteSettings.ADMIN_DOCUMENT_ROOT + "post/feature.php",
    PUBLISH_LINK: MB.CONST.SiteSettings.ADMIN_DOCUMENT_ROOT + "post/publish.php",
    REMOVE_LINK: MB.CONST.SiteSettings.ADMIN_DOCUMENT_ROOT + "post/remove.php"
};

function PostModel(postId, postSlug, postTitle, postContent, postCategory, date, published, featured, categoryMap) {
    var self = this;

    self.postId = postId;
    self.postSlug = postSlug;
    self.postTitle = postTitle;
    self.postContent = postContent;
    self.postCategoryId = postCategory;
    self.postCategoryName = categoryMap[postCategory];
    self.publishDate = moment.unix(date).format("MMMM Do, YYYY");
    self.published = ko.observable(parseInt(published));
    self.featured = ko.observable(parseInt(featured));
    // Convenience links
    self.postCategoryLink = ko.computed(function() {
        if(self.postCategoryId) {
            return MB.CONST.SiteSettings.DOCUMENT_ROOT + "?category=" + self.postCategoryName;
        }
        else {
            return undefined;
        }
    });
    self.editLink = MB.CONST.SiteSettings.ADMIN_DOCUMENT_ROOT + "admin.php?mode=edit&id=" + postId;
    self.viewLink = ko.computed(function() {
        var first = MB.CONST.SiteSettings.DOCUMENT_ROOT + "?post=" + self.postSlug;
        if(self.published()) {
            return first;
        }
        else {
            return first + "&preview=y";
        }
    });
}

function PostListViewModel() {
    var self = this;

    self.posts = ko.observableArray();
    self.loaded = ko.observable(false);
    self.categoryMap = ko.observableArray();
    self.categoriesMapped = ko.observable(false);
    self.postsLoaded = ko.observable(false);
    self.featuredPost = ko.observable();

    self.initData = function() {
        $.getJSON("../adm/post/", function(allData) {
            var mappedPosts = ko.utils.arrayMap(allData, function(item) {
                return new PostModel(
                    item.post_id,
                    item.post_slug,
                    item.post_title,
                    item.post_content,
                    item.post_category,
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
    self.getFeaturedPost = function() {
        $.getJSON(MB.CONST.Posts.FEATURE_LINK, function(post) {
            // Should just be one post id
            if(post.featured !== undefined) {
                self.featuredPost(post.featured);
            }
        });
    }

    /**
     * publishPost will "flip the bit" of the published status, so if it is published,
     *     it will be unpublished, and vice versa.
     */
    self.publishPost = function(post) {
        alert(ko.unwrap(post.published));
        var data = {
            "postid": post.postId,
            "published": (post.published() ? 0 : 1)
        }
        $.post(MB.CONST.Posts.PUBLISH_LINK, data, function(returnedData) {
            //post.published(returnedData.published);
            self.featuredPost()
        });
    };

    self.featurePost = function(post) {
        var data = {
            "postid": post.postId
        }
        $.post(MB.CONST.Posts.FEATURE_LINK, data, function(returnedData) {
            post.featured(returnedData.featured);
        });
    };

    self.deletePost = function(post) {
        if(confirm("Are you sure you want to delete the post " + post.postTitle + "?")) {
            var data = {
                "postid": post.postId
            };
            $.post(MB.CONST.Posts.REMOVE_LINK, data, function(returnedData) {
                self.posts.remove(post);
            });
        }
    };

    self.getCategoryMappings();

    self.getFeaturedPost();
}
