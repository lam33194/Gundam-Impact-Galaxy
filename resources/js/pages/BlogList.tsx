import { useEffect, useState } from "react";
import Blog from "../components/Blog";
import Product from "../components/Product";
import "./BlogList.scss";
import { getAllBlogs } from "../services/BlogService";

function BlogList() {
    const [blogList, setBlogList] = useState([]);

    const getBlogList = async() =>{
        try {
            const res = await getAllBlogs();
            if (res && res.data){
                setBlogList(res.data.data);
                console.log(res.data.data);
            }
        } catch (error) {
            
        }
    }

    useEffect(() =>{
        getBlogList();
    },[])
    return (
        <div className="container">
            <div className="nav d-flex align-items-center mb-2">
                <a href="" className="text-decoration-none text-dark fw-bold">
                    Trang chủ
                </a>
                <span className="mx-2">/</span>
                <a href="" className="text-decoration-none text-secondary">
                    Tin tức
                </a>
            </div>
            <div className="blog-list-page d-flex gap-5">
                <div className="left col-9">
                    <h4 className="fw-bold my-3">TIN TỨC</h4>
                    <div className="blog-list">
                    {blogList &&
                            blogList.length > 0 &&
                            blogList.map((b: any) => {
                                return (
                                    <div key={b.id} className="blog-item">
                                        <Blog
                                            display={"column"}
                                            backgroundSize="100% 100%"
                                            blog={b}
                                        />
                                    </div>
                                );
                            })}
                    </div>
                </div>

                <div className="right col-3">
                    <h4 className="my-3 fw-bold">TIN NỔI BẬT</h4>
                    <div className="outstanding-blogs d-flex flex-column gap-1">
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
                        <Product />
                        <Product />
                        <Product />
                        <Product />
                    </div>
                </div>
            </div>
        </div>
    );
}

export default BlogList;
