/**
 * Knockout model for posts
 */
function PostModel(postId, postSlug, postTitle, postContent, postCategory, postCategoryName, date, published, featured) {
    var self = this;

    self.postId = parseInt(postId);
    self.postSlug = postSlug;
    self.postUrl = MB.CONST.SiteSettings.DOCUMENT_ROOT + '?post=' + self.postSlug;
    self.postTitle = postTitle;
    self.postContent = postContent;
    self.postCategoryId = postCategory;
    self.postCategoryName = postCategoryName;
    self.publishDate = moment.unix(date).format("MMMM Do, YYYY");
    self.published = ko.observable(published == 1);
    self.featured = ko.observable(featured == 1);
    // Convenience links
    self.postCategoryLink = ko.computed(function() {
        if(self.postCategoryId) {
            return MB.CONST.SiteSettings.DOCUMENT_ROOT + "?category=" + self.postCategoryName;
        }
        else {
            return undefined;
        }
    });
    self.editLink = MB.CONST.SiteSettings.ADMIN_DOCUMENT_ROOT + "edit.php?mode=edit&id=" + postId;
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
