/**
 * Listing posts
 */
var MB = (MB || {});

ko.bindingHandlers.yourBindingName = {
    init: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
        // This will be called when the binding is first applied to an element
        // Set up any initial state, event handlers, etc. here

    },
    update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
        // This will be called once when the binding is first applied to an element,
        // and again whenever any observables/computeds that are accessed change
        // Update the DOM element based on the supplied values here.
    }
};

var postTemplate = '' +
    '<script type="text/html" id="post-template">' +
        '<div class="post" data-bind="\'attr\': { \'id\': postId }">' +
            '<h4><a data-bind="\'attr\': { \'href\': postUrl }, \'text\': postTitle"></a></h4>' +
            '<span class="date" data-bind="\'text\': publishDate"></span>' +
            '<div class="post-content" data-bind="\'html\': postContent"></div>' +
            '<a data-bind="\'href\': postCategoryLink">View more posts from this category</a>' +
        '</div>' +
    '</script>';

function PostViewModel(postData, featuredPostData, singlePost) {
    var self = this;
    self.postData = postData;
    self.featuredPostData = featuredPostData;
    self.singlePost = singlePost;
    self.posts = ko.observableArray();
    self.featuredPost = ko.observable();

    self.initData = function() {
        ko.utils.arrayForEach(self.postData, function(item) {
            if(item.featured != 1 || self.singlePost !== "") {
                var newPost = new PostModel(
                    item.post_id,
                    item.post_slug,
                    item.post_title,
                    item.post_content,
                    item.post_category,
                    item.post_category_name,
                    item.date,
                    item.published,
                    item.featured
                );
                self.posts.push(newPost);
            }
        });

        if(self.featuredPostData !== null) {
            self.featuredPost(new PostModel(
                self.featuredPostData.post_id,
                self.featuredPostData.post_slug,
                self.featuredPostData.post_title,
                self.featuredPostData.post_content,
                self.featuredPostData.post_category,
                self.featuredPostData.post_category_name,
                self.featuredPostData.date,
                self.featuredPostData.published,
                self.featuredPostData.featured
            ));
        }
    };

    self.initData();


}
