/**
 * Listing posts
 */
var MB = (MB || {});

function PostViewModel(postData, featuredPostData) {
    var self = this;
    self.postData = postData;
    self.featuredPostData = featuredPostData
    self.posts = ko.observableArray();
    self.featuredPost = ko.observable();

    self.initData = function() {
        ko.utils.arrayForEach(self.postData, function(item) {
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
            if(item.featured != 1) {
                self.posts.push(newPost);
            }
        });

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
        ))
    };

    self.initData();


}
