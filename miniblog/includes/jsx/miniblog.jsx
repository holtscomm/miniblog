;(function (React, MB) {
  'use strict';

  var Post = React.createClass({
    render: function () {
      return (<div className="post">
        <h4><a href="{this.props.postUrl}">{this.props.postTitle}</a></h4>
        <span className="date">{this.props.publishDate}</span>
        <div className="post-content" postContent></div>
        <a href="{this.props.postCategoryLink}">View more posts from this category</a>
      </div>);
    }
  });

  var PostList = React.createClass({
    render: function () {
      return (<div>Something awesome</div>);
    }
  });

  MB.renderPosts = function (posts, featured, single) {
    if (single) {
      // Do something a bit differently
    }

    React.render(<PostList posts=posts />, document.getElementById('attacher'));
  }
} (window.React, window.MB))
