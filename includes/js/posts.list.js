/**
 * Listing posts
 */

DOCUMENT_ROOT = "/miniblog/";

function PostModel(postId, postSlug, postTitle, postContent, postCategory, date, published, categoryMap) {
    var self = this;
    self.postId = postId;
    self.postSlug = postSlug;
    self.postTitle = postTitle;
    self.postContent = postContent;
    self.postCategoryId = postCategory;
    self.postCategoryName = categoryMap[postCategory];
    self.postCategoryLink = ko.computed(function() {
        return DOCUMENT_ROOT + "?category=postCategory";
    });
    self.publishDate = moment.unix(date).format("MMMM Do, YYYY");
    self.published = published == 1;
    self.publishLink = ko.computed(function() {
        return "post/publish.php?postid=" + self.postId + "&published=" + published;
    });
    // self.previewLink = ko.computed then make the link!
}

function PostListViewModel() {
    var self = this;

    self.posts = ko.observableArray();

    self.categoryMap = ko.observableArray();

    self.initData = function() {
        $.getJSON("../adm/post/", function(allData) {
            // debugger;
            var mappedPosts = $.map(allData, function(item) {
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
        });
    }

    self.getCategoryMappings();

    self.initData();
}
