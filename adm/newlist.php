<?php
include('header.php');
?>

<table>
    <thead>
        <tr>
            <th>Post name</th>
            <th>Post Category</th>
            <th>Posted</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody data-bind="foreach: posts">
        <tr>
            <td data-bind="text: postSlug"></td>
            <td><a data-bind="attr: { href: postCategoryLink }"><span data-bind="text: postCategoryName"></span></a></td>
            <td data-bind="text: publishDate"></td>
            <!-- ko if: published -->
            <td>Published <a data-bind="attr: { href: publishLink }">Unpublish?</a></td>
            <!-- /ko -->
            <!-- ko if: !published -->
            <td>Unpublished <a data-bind="attr: { href: publishLink }">Publish?</a></td>
            <!-- /ko -->
            <td data-bind="text: postCategoryId"></td>
        </tr>
    </tbody>
</table>


<script type="text/javascript">
    ko.applyBindings(new PostListViewModel());
</script>
<?php
include('footer.php');
?>
