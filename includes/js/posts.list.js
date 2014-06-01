/**
 * Listing posts
 */
var MB = (MB || {});

PUBLISH_LINK = ADMIN_DOCUMENT_ROOT + "post/publish.php";

function PostModel(postId, postSlug, postTitle, postContent, postCategory, date, published, categoryMap) {
    var self = this;

    self.postId = postId;
    self.postSlug = postSlug;
    self.postTitle = postTitle;
    self.postContent = postContent;
    self.postCategoryId = postCategory;
    self.postCategoryName = categoryMap[postCategory];
    self.publishDate = moment.unix(date).format("MMMM Do, YYYY");
    self.published = ko.observable(parseInt(published));
    // Convenience links
    self.postCategoryLink = DOCUMENT_ROOT + "?category=postCategory";
    self.editLink = ADMIN_DOCUMENT_ROOT + "admin.php?mode=edit&id=" + postId;
    self.viewLink = ko.computed(function() {
        var first = DOCUMENT_ROOT + "?post=" + self.postSlug;
        if(self.published()) {
            return first;
        }
        else {
            return first + "&preview=y";
        }
    });
    self.deleteLink = ADMIN_DOCUMENT_ROOT + "post/delete.php?postid=" + self.postId;
}

function PostListViewModel() {
    var self = this;

    self.posts = ko.observableArray();
    self.loaded = ko.observable(false);
    self.categoryMap = ko.observableArray();

    self.initData = function() {
        $.getJSON("../adm/post/", function(allData) {
            // debugger;
            var mappedPosts = ko.utils.arrayMap(allData, function(item) {
                return new PostModel(
                    item.post_id,
                    item.post_slug,
                    item.post_title,
                    item.post_content,
                    item.post_category,
                    item.date,
                    item.published,
                    self.categoryMap()
                );
            });
            self.posts(mappedPosts);
        });
    }

    self.getCategoryMappings = function() {
        $.getJSON("../adm/category/map.php", function(categories) {
            var mappedCategories = new Array();
            ko.utils.arrayForEach(categories, function(category) {
                mappedCategories[category.cat_id] = category.name;
            });

            self.categoryMap(mappedCategories);

            self.initData();
        });
    }

    self.publishPost = function(post) {
        var data = {
            "postid": post.postId,
            "published": post.published()
        }
        // Using $.post to do a POST request to the publishLink
        $.post(PUBLISH_LINK, data, function(returnedData) {
            post.published(returnedData.published)
        });

    }

    self.getCategoryMappings();
}
