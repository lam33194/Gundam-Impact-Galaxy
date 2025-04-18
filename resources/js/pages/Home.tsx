import { Carousel } from "react-bootstrap";
import "./Home.scss";
import Product from "../components/Product";
import Coupon from "../components/Coupon";
import Blog from "../components/Blog";
import { useEffect, useState } from "react";
import { getAll } from "../services/ProductService";

function Home() {
    const [products, setProducts] = useState([]);
    const getAllProducts = async () => {
        try {
            const res = await getAll();
            if (res.data && res.data.data) {
                console.log(res.data.data);
                setProducts(res.data.data);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const redirectToDetail = (slug: any) => {
        window.location.href = "/product/" + slug;
    };

    useEffect(()=>{
        getAllProducts();
    }, [])
    return (
        <div className="home-container container flex-column d-flex gap-5">
            <Carousel prevLabel="Previous" nextLabel="Next">
                <Carousel.Item>
                    <img
                        className="d-block w-100"
                        src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/slider_2.jpg?1740630578329" // Thay thế bằng URL hình ảnh sản phẩm
                        alt="First slide"
                    />
                </Carousel.Item>

                <Carousel.Item>
                    <img
                        className="d-block w-100"
                        src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/slider_2.jpg?1740630578329"
                        alt="Second slide"
                    />
                </Carousel.Item>
            </Carousel>

            <div className="best-seller">
                <h4 className="fw-bold text-uppercase fs-5 mb-3">
                    Sản phẩm bán chạy
                </h4>
                <div className="product-list">
                    {products &&
                        products.map((p, index) => {
                            return (
                                <div className=""   onClick={() => redirectToDetail(p.slug)}>
                                     <Product key={index} p={p} />
                                </div>
                            );
                        })}
                </div>
            </div>
            <div className="banner row ">
                <img
                    className="col-6"
                    height={"280px"}
                    src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/imgbanner1.jpg?1740630578329"
                />
                <img
                    className="col-6"
                    height={"280px"}
                    src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/imgbanner2.jpg?1740630578329"
                />
            </div>
            <div className="coupon-list gap-2">
                <Coupon />
                <Coupon />
                <Coupon />
            </div>

            <div className="blog-list-home">
                <h4 className="fw-bold text-uppercase fs-5">BLOG TIN TỨC</h4>
                <div className="list row">
                    <div className="col-6">
                        <Blog display={"column"} />
                    </div>
                    <div className="d-flex flex-column gap-2 col-6">
                        <Blog display={"row"} />
                        <Blog display={"row"} />
                        <Blog display={"row"} />
                    </div>
                </div>
            </div>
            <div className="best-seller">
                <h4 className="fw-bold text-uppercase fs-5">CÓ THỂ BẠN THÍCH</h4>
                <div className="product-list">
                    {products &&
                        products.map((p, index) => {
                            return (
                                <div className=""   onClick={() => redirectToDetail(p.slug)}>
                                     <Product key={index} p={p} />
                                </div>
                            );
                        })}
                </div>
            </div>
            <div className="service-info row">
                <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
                    <img
                        width={"50px"}
                        height={"50px"}
                        src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv1.png?1740630578329"
                    />
                    <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                </div>

                <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
                    <img
                        width={"50px"}
                        height={"50px"}
                        src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv2.png?1740630578329"
                    />
                    <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                </div>

                <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
                    <img
                        width={"50px"}
                        height={"50px"}
                        src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv3.png?1740630578329"
                    />
                    <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                </div>

                <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
                    <img
                        width={"50px"}
                        height={"50px"}
                        src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv4.png?1740630578329"
                    />
                    <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                </div>
            </div>
        </div>
    );
}

export default Home;
