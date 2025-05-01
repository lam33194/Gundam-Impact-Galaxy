import { FormatDate } from "../utils/FormatDate";
import "./Blog.scss";
import DOMPurify from "dompurify";

function Blog({ display, backgroundSize = "cover", blog }) {
    const cleanHtml = DOMPurify.sanitize(blog?.content || "");
    return (
        <div
            className={
                display === "column"
                    ? "blog d-flex flex-column gap-1"
                    : "blog d-flex gap-2"
            }
            onClick={() => window.location.href="/blog-detail/" + blog?.slug}
        >
            <div
                className="blog-image"
                style={{
                    backgroundSize: backgroundSize,
                    backgroundImage: blog?.thumbnail,
                    // "url(https://bizweb.dktcdn.net/100/456/060/articles/review-mo-hinh-robo-trai-cay-quyt-kiem-si.png?v=1736671380983)",
                    height:
                        display === "column"
                            ? backgroundSize === "cover"
                                ? "250px"
                                : "150px"
                            : "95px",
                }}
            ></div>

            <div className="main d-flex flex-column justify-content-center">
                <span className="fw-bold title">
                    {blog?.title}
                    {/* Review mô hình Robo Quýt Kiếm Sĩ - Những cải tiến đáng kể bạn cần
          biết? */}
                </span>
                <span className="date">{blog?.updated_at}</span>
                <div
                    className="content "
                    dangerouslySetInnerHTML={{ __html: cleanHtml }}
                />
            </div>
        </div>
    );
}

export default Blog;
