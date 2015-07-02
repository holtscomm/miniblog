;(function (React, $) {
  'use strict';

  var Post = React.createClass({displayName: "Post",
    render: function () {
      return (React.createElement("div", {className: "post"}, 
        React.createElement("h4", null, React.createElement("a", {href: "{this.props.postUrl}"}, this.props.postTitle)), 
        React.createElement("span", {className: "date"}, this.props.publishDate), 
        React.createElement("div", {className: "post-content", postContent: true}), 
        React.createElement("a", {href: "{this.props.postCategoryLink}"}, "View more posts from this category")
      ));
    }
  });

  var PostList = React.createClass({displayName: "PostList",
    render: function () {
      return (React.createElement("div", null, "Something awesome"));
    }
  });

  React.render(React.createElement(PostList, null), document.getElementById('attacher'));
} (window.React, window.$))
