<?php
include('header.php');
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Post Name</th>
            <th>Post Category</th>
            <th>Published?</th>
            <th>Featured?</th>
            <th>Posted Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody data-bind="foreach: posts">
        <tr>
            <td>
                <a data-bind="attr: { href: viewLink }, text: postTitle" target="_blank"></a>
                (<a data-bind="attr: { href: editLink }">edit</a>)
            </td>
            <td>
                <a data-bind="attr: { href: postCategoryLink }"><span data-bind="text: postCategoryName"></span></a>
                <span data-bind="ifnot: postCategoryId">&lt;none&gt;</span>
            </td>
            <td><input type="checkbox" data-bind="checked: published, click: $parent.publishPost"/></td>
            <td><input type="radio" name="featuredGroup" data-bind="value: postId, enable: published, checked: $parent.featuredPostId"/></td>
            <td data-bind="text: publishDate"></td>
            <td><a href="#" data-bind="click: $parent.deletePost.bind($data)">Delete post</a></td>
        </tr>
    </tbody>
</table>

<a href="admin.php?mode=add"><button class="btn btn-success">Add a new post</button></a>

<div data-bind="ifnot: postsLoaded" class="text-center">
    <img src="../includes/spinnerLarge.gif" title="Loading posts" />
    <h3>Loading posts...</h3>
</div>


<script type="text/javascript">
    $(function() {
        ko.applyBindings(new PostListViewModel());
    });
</script>
<?php
include('footer.php');
?>
