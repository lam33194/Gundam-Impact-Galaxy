import Product from "../components/Product";
import "./BlogDetails.scss";
function BlogDetail() {
    return (
        <div className="blog-detail container d-flex flex-column gap-4">
            <div className="nav d-flex align-items-center">
                <a href="" className="text-decoration-none text-primary">
                    Trang chủ
                </a>
                <span className="mx-2">/</span>
                <a href="" className="text-decoration-none text-primary">
                    Tin tức
                </a>
                <span className="mx-2">/</span>
                <span className="text-muted">
                    Cách Lắp Ráp Mô Hình Gundam MG Cho Người Mới: Hướng Dẫn Chi
                    Tiết Từ A-Z
                </span>
            </div>

            <div className="main-content d-flex gap-4">
                <div className="left col-3 d-flex flex-column gap-3">
                    <h4>TIN NỔI BẬT</h4>
                    <div className="outstanding-blogs d-flex flex-column gap-3">
                        <div className="outstanding-blog d-flex gap-2">
                            <img
                                className="col-5"
                                src="https://bizweb.dktcdn.net/100/456/060/files/review-mo-hinh-robo-trai-cay-quyt-kiem-si.png?v=1736671376577"
                                height={"64px"}
                            ></img>
                            <p className="col-7 pe-2">
                                Cách Lắp Ráp Mô Hình Gundam MG Cho Người
                            </p>
                        </div>
                        <div className="line"></div>
                        <div className="outstanding-blog d-flex gap-2">
                            <img
                                className="col-5"
                                src="https://bizweb.dktcdn.net/100/456/060/files/review-mo-hinh-robo-trai-cay-quyt-kiem-si.png?v=1736671376577"
                                height={"64px"}
                            ></img>
                            <p className="col-7 pe-2">
                                Cách Lắp Ráp Mô Hình Gundam MG Cho Người
                            </p>
                        </div>
                        <div className="line"></div>
                        <div className="outstanding-blog d-flex gap-2">
                            <img
                                className="col-5"
                                src="https://bizweb.dktcdn.net/100/456/060/files/review-mo-hinh-robo-trai-cay-quyt-kiem-si.png?v=1736671376577"
                                height={"64px"}
                            ></img>
                            <p className="col-7 pe-2">
                                Cách Lắp Ráp Mô Hình Gundam MG Cho Người
                            </p>
                        </div>
                        <div className="line"></div>
                    </div>

                    <h4>SẢN PHẨM NỔI BẬT</h4>
                    <div className="outstanding-products d-flex flex-column gap-3">
                        <Product/>
                        <Product/>
                        <Product/>
                        <Product/>
                    </div>
                </div>
                <div className="right col-9">
                    <div className="blog">
                        <h3>
                            Review mô hình Robo Quýt Kiếm Sĩ - Những cải tiến
                            đáng kể bạn cần biết?
                        </h3>
                        <span>
                            Người đăng: <strong>HAKUDA</strong> | 12/01/2025
                        </span>
                        <div
                            className="blog-img my-4"
                            style={{
                                backgroundImage:
                                    "url(https://bizweb.dktcdn.net/100/456/060/files/review-mo-hinh-robo-trai-cay-quyt-kiem-si.png?v=1736671376577)",
                            }}
                        ></div>
                        <p>
                            Mình thật sự rất mong chờ mẫu sản phẩm lần này,
                            không chỉ vì nó có những sự cải thiện về khớp để
                            tăng cường biên độ cử động hay là sự thay đổi về mặt
                            thiết kế để có thể bám sát với nguyên tác phim hơn
                            mà còn là về tin đồn rằng ở mẫu mô hình lần này sẽ
                            được trang bị cả đèn Led - hứa hẹn một trải nghiệm
                            đỉnh nóc, kịch trần, bay phấp phới dành cho người
                            chơi. Vậy thì không để các bạn chờ lâu nữa, hãy cùng
                            mình khám phá ngay về chàng đội trưởng nhóm nam của
                            Đội Đặc Nhiệm Trái Cây – Quýt Ú, Quýt Kiếm Sĩ nhé!
                        </p>
                        <p>
                            {" "}
                            1. Story - Câu Chuyện Quýt Ú, tên thật là Tranh Lưu
                            Hương, là một kiếm khách xuất thân nghèo khổ, không
                            một xu dính túi nhưng mang trong mình ý chí mãnh
                            liệt và ước mơ cháy bỏng trở thành một chiến binh
                            mạnh mẽ để góp phần xây dựng quê hương. Là trưởng
                            nhóm nam trong đội Đặc Nhiệm Trái Cây, Quýt Ú không
                            có sự giàu có hay khả năng làm thơ như Táo Ngố, cũng
                            không sở hữu sự thông minh và vẻ điển trai của Thơm
                            Lùn, càng không biết cách nói lời hoa mỹ. Tuy vậy,
                            cậu luôn nổi bật với trái tim ngay thẳng, tinh thần
                            dũng cảm, và một ý chí không gì lay chuyển.
                        </p>
                    </div>
                    <div className="comment mt-5">
                        <h5>Thảo luận về chủ đề này</h5>
                        <div className="form-group mt-3">
                            <input
                                type="text"
                                placeholder="Họ tên"
                                className="form-control mb-3"
                            />
                            <input
                                type="text"
                                placeholder="Họ tên"
                                className="form-control mb-3"
                            />
                            <textarea
                                rows={4}
                                name=""
                                id=""
                                placeholder="Nội dung"
                                className="form-control mb-3"
                            ></textarea>
                            <button type="submit" className="btn btn-dark">
                                Gửi bình luận
                            </button>
                        </div>
                    </div>
                    <h3 className="text-center mb-5 mt-5">
                        Danh sách Bình luận
                    </h3>
                    <div className="list-group">
                        <div className="list-group-item d-flex flex-column p-3 bg-light border-bottom pb-3">
                            <div className="d-flex justify-content-between align-items-start mb-2">
                                <h5 className="fw-bold mb-1">Nguyễn Văn A</h5>
                                <small className="text-muted">
                                    2025-03-13 10:00
                                </small>
                            </div>
                            <div className="text-muted mb-2">
                                nguyen.a@example.com
                            </div>
                            <p className="mb-0">
                                Đây là một bình luận mẫu để hiển thị nội dung
                                bình luận của người dùng.
                            </p>
                        </div>

                        <div className="list-group-item d-flex flex-column p-3 bg-light border-bottom pb-3">
                            <div className="d-flex justify-content-between align-items-start mb-2">
                                <h5 className="fw-bold mb-1">Trần Thị B</h5>
                                <small className="text-muted">
                                    2025-03-13 11:30
                                </small>
                            </div>
                            <div className="text-muted mb-2">
                                tran.b@example.com
                            </div>
                            <p className="mb-0">
                                Đây là một bình luận khác để minh họa cách hiển
                                thị.
                            </p>
                        </div>

                        <div className="list-group-item d-flex flex-column p-3 bg-light">
                            <div className="d-flex justify-content-between align-items-start mb-2">
                                <h5 className="fw-bold mb-1">Lê Văn C</h5>
                                <small className="text-muted">
                                    2025-03-13 12:45
                                </small>
                            </div>
                            <div className="text-muted mb-2">
                                le.c@example.com
                            </div>
                            <p className="mb-0">
                                Nội dung bình luận thứ ba của người dùng.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default BlogDetail;
