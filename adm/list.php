<?php
include('header.php');
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Post Name</th>
            <th>Post Category</th>
            <th>Status</th>
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
            <!-- ko if: published -->
            <td>Published - <a href="#" data-bind="click: $parent.publishPost">Unpublish?</a></td>
            <!-- /ko -->
            <!-- ko ifnot: published -->
            <td>Unpublished - <a href="#" data-bind="click: $parent.publishPost">Publish?</a></td>
            <!-- /ko -->
            <td data-bind="text: publishDate"></td>
            <td><a href="#" data-bind="click: $parent.deletePost.bind($data)">Delete post</a></td>
        </tr>
    </tbody>
</table>

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
