import "./Blog.scss";

function Blog({ display, backgroundSize = 'cover' }) {
  return (
    <div
      className={
        display === "column"
          ? "blog d-flex flex-column gap-1"
          : "blog d-flex gap-2"
      }
    >
      <div
        className="blog-image"
        style={{
          backgroundSize: backgroundSize,
          backgroundImage:
            "url(https://bizweb.dktcdn.net/100/456/060/articles/review-mo-hinh-robo-trai-cay-quyt-kiem-si.png?v=1736671380983)",
          height: display === "column" ? (backgroundSize === "cover" ? "250px" : "150px") : "95px",
        }}
      ></div>

      <div className="main d-flex flex-column justify-content-center">
        <span className="fw-bold title">
          Review mô hình Robo Quýt Kiếm Sĩ - Những cải tiến đáng kể bạn cần
          biết?
        </span>
        <span className="date">12/01/2025</span>
        <span className="content">
          Như vậy là sau một thời gian dài chờ đợi thì cuối cùng mẫu mô hình
          Quýt Kiếm Sĩ đã chính thức ra Như vậy là sau một thời gian dài chờ đợi
          thì cuối cùng mẫu mô hình Quýt Kiếm Sĩ đã chính thức ra...
        </span>
      </div>
    </div>
  );
}

export default Blog;
