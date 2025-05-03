import { useParams } from "react-router-dom";
import Product from "../components/Product";
import "./BlogDetails.scss";
import { useEffect, useState } from "react";
import { getBlogById } from "../services/BlogService";
import DOMPurify from "dompurify";
import { STORAGE_URL } from "../utils/constants";
import { getTopRevenue } from "../services/ProductService";

function BlogDetail() {
    const product = {
        id: 1,
        name: "GUndam galaxy ",
        price: 1000,
        image: "https://i.imgur.com/6yQXxuB.jpg",
    };

    const [blog, setBlog] = useState<any>();
    const [cleanHtml, setCleanHtml] = useState<any>();
    const [topRevenue, setTopRevenue] = useState<any>();

    const { id } = useParams();
    const getTopRevenueProducts = async () => {
        try {
            const res = await getTopRevenue();
            if (res && res.data) {
                setTopRevenue(res.data.data);
            }
        } catch (error) {
            console.log(error);
        }
    };
    useEffect(() => {
        const getDetail = async () => {
            try {
                const res = await getBlogById(id);
                if (res && res.data) {
                    setBlog(res.data.data);
                    console.log(res.data);
                    setCleanHtml(DOMPurify.sanitize(res.data.data.content || ""));
                }
            } catch (error) {}
        };
        getDetail();
        getTopRevenueProducts();
    }, [id]);
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
                <span className="text-muted">{blog?.title}</span>
            </div>

            <div className="main-content d-flex gap-5">
                <div className="left col-3 d-flex flex-column gap-3">
                    <h4>TIN NỔI BẬT</h4>
                    <div className="outstanding-blogs d-flex flex-column gap-3">
                        <div className="outstanding-blog d-flex gap-2">
                            <img
                                className="col-5"
                                src="https://bizweb.dktcdn.net/100/456/060/files/review-mo-hinh-robo-trai-cay-quyt-kiem-si.png?v=1736671376577"
                            ></img>
                            <p className="col-7 pe-2">
                                Cách Lắp Ráp Mô Hình Gundam MG Cho Người
                            </p>
                        </div>
                        <div className="line my-1"></div>
                        <div className="outstanding-blog d-flex gap-2">
                            <img
                                className="col-5"
                                src="https://bizweb.dktcdn.net/100/456/060/files/review-mo-hinh-robo-trai-cay-quyt-kiem-si.png?v=1736671376577"
                            ></img>
                            <p className="col-7 pe-2">
                                Cách Lắp Ráp Mô Hình Gundam MG Cho Người
                            </p>
                        </div>
                        <div className="line my-1"></div>
                        <div className="outstanding-blog d-flex gap-2">
                            <img
                                className="col-5"
                                src="https://bizweb.dktcdn.net/100/456/060/files/review-mo-hinh-robo-trai-cay-quyt-kiem-si.png?v=1736671376577"
                            ></img>
                            <p className="col-7 pe-2">
                                Cách Lắp Ráp Mô Hình Gundam MG Cho Người
                            </p>
                        </div>
                        <div className="line my-1"></div>
                    </div>

                    <h4>SẢN PHẨM NỔI BẬT</h4>
                    <div className="outstanding-products d-flex flex-column gap-3">
                        {topRevenue &&
                            topRevenue.map((p: any) => {
                                return <Product key={p.id} p={p} />;
                            })}
                    </div>
                </div>
                <div className="right col-9">
                    <div className="blog">
                        <h3>{blog?.title}</h3>
                        <span>
                            Người đăng: <strong>{blog?.user?.name}</strong> |
                            12/01/2025
                        </span>
                        <div
                            className="blog-img my-4"
                            style={{
                                backgroundImage: `url(${
                                    STORAGE_URL + blog?.thumbnail
                                } )`,
                            }}
                        ></div>
                        <div
                            className="blog-content "
                            dangerouslySetInnerHTML={{ __html: cleanHtml }}
                        />
                    </div>
                    {/* <div className="comment mt-5">
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
                    </div> */}
                </div>
            </div>
        </div>
    );
}

export default BlogDetail;
