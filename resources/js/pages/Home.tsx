import { Carousel } from "react-bootstrap";
import "./Home.scss";
import Product from "../components/Product";
import Voucher from "../components/Voucher";
import Blog from "../components/Blog";
import { useEffect, useState, useCallback } from "react";
import {
    getAll,
    getTopRevenue,
    getTopSelling,
} from "../services/ProductService";
import { getVouchers } from "../services/VoucherService";
import { useHorizontalScroll } from "../hooks/useHorizontalScroll";
import { useScrollable } from "../hooks/useScrollable";
import ico_sv1 from "../assets/ico_sv1.png";
import ico_sv2 from "../assets/ico_sv2.webp";
import ico_sv3 from "../assets/ico_sv3.webp";
import ico_sv4 from "../assets/ico_sv4.png";
import { useNavigate } from "react-router-dom";
import { getAllBlogs } from "../services/BlogService";

function Home() {
    const [products, setProducts] = useState<any[]>([]);
    const [revenueProducts, setRevenueProducts] = useState([]);
    const [sellingProducts, setSellingProducts] = useState<any[]>([]);
    const [vouchers, setVouchers] = useState([]);

    const containerId = "voucherList";
    const canScroll = useScrollable(containerId, 4, vouchers);
    useHorizontalScroll(containerId, canScroll, 0.5);

    const [blogList, setBlogList] = useState([]);
    const nav = useNavigate();

    const getBlogList = async () => {
        try {
            const res = await getAllBlogs();
            if (res && res.data) {
                setBlogList(res.data.data);
                console.log(res.data.data);
            }
        } catch (error) {

        }
    }

    useEffect(() => {
        getBlogList();
    }, [])

    const getAllProducts = async () => {
        try {
            const res = await getAll();
            if (res.data && res.data.data) {
                setProducts(res.data.data);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const getTopRevenueProducts = async () => {
        try {
            const res = await getTopRevenue();
            if (res.data && res.data.data) {
                setRevenueProducts(res.data.data);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const getTopSellingProducts = async () => {
        try {
            const res = await getTopSelling();
            if (res.data && res.data.data) {
                setSellingProducts(res.data.data);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const getAllVouchers = async () => {
        try {
            const res = await getVouchers();
            if (res.data && res.data.data) {
                setVouchers(res.data.data);
            }
        } catch (error) {
            console.log("Error fetching vouchers:", error);
        }
    };

    const redirectToDetail = (slug: any) => {
        window.location.href = "/product/" + slug;
    };

    const scrollVouchers = useCallback((direction: "left" | "right") => {
        const container = document.getElementById(containerId);
        if (!container) return;

        const scrollAmount = 400;
        const scrollPosition =
            direction === "left"
                ? container.scrollLeft - scrollAmount
                : container.scrollLeft + scrollAmount;

        container.scrollTo({
            left: scrollPosition,
            behavior: "smooth",
        });
    }, []);

    useEffect(() => {
        getAllProducts();
        getTopRevenueProducts();
        getTopSellingProducts();
        getAllVouchers();
    }, []);
    return (
        <div className="home-container container flex-column d-flex gap-5">
            <Carousel prevLabel="Previous" nextLabel="Next">
                <Carousel.Item>
                    <img
                        className="d-block w-100"
                        src="https://bizweb.dktcdn.net/100/456/060/themes/1004041/assets/slider_1.jpg?1744256862985"
                        alt="First slide"
                    />
                </Carousel.Item>

                <Carousel.Item>
                    <img
                        className="d-block w-100"
                        src="https://bizweb.dktcdn.net/100/456/060/themes/1004041/assets/slider_1.jpg?1744256862985"
                        alt="Second slide"
                    />
                </Carousel.Item>
            </Carousel>

            <div className="best-seller">
                <h4 className="fw-bold text-uppercase fs-5 mb-3">
                    Sản phẩm bán chạy
                </h4>
                <div className="product-list">
                    {sellingProducts &&
                        sellingProducts.map((p, index) => {
                            return (
                                <div
                                    className=""
                                    onClick={() => redirectToDetail(p.slug)}
                                >
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
            <div className="voucher-section position-relative">
                {canScroll && (
                    <div
                        className="scroll-button scroll-left"
                        onClick={() => scrollVouchers("left")}
                    >
                        <i className="fas fa-chevron-left"></i>
                    </div>
                )}

                <div
                    className="coupon-list gap-2"
                    id={containerId}
                    style={{
                        overflowX: canScroll ? "auto" : "hidden",
                        cursor: canScroll ? "grab" : "default",
                    }}
                >
                    {vouchers.map((voucher, index) => (
                        <Voucher key={index} voucher={voucher} />
                    ))}
                </div>

                {canScroll && (
                    <div
                        className="scroll-button scroll-right"
                        onClick={() => scrollVouchers("right")}
                    >
                        <i className="fas fa-chevron-right"></i>
                    </div>
                )}
            </div>

            <div className="blog-list-home">
                <h4 className="fw-bold text-uppercase fs-5">BLOG TIN TỨC</h4>
                <div className="list row">
                    <div className="col-6">
                        <Blog
                            display={"column"}
                            backgroundSize="100% 100%"
                            blog={blogList[0]}
                        />
                    </div>
                    <div className="d-flex flex-column gap-2 col-6">
                        <Blog
                            display={"column"}
                            backgroundSize="100% 100%"
                            blog={blogList[1]}
                        />
                        <Blog
                            display={"column"}
                            backgroundSize="100% 100%"
                            blog={blogList[1]}
                        />
                        <Blog
                            display={"column"}
                            backgroundSize="100% 100%"
                            blog={blogList[1]}
                        />
                    </div>
                </div>
            </div>
            <div className="best-seller">
                <h4 className="fw-bold text-uppercase fs-5">
                    CÓ THỂ BẠN THÍCH
                </h4>
                <div className="product-list">
                    {products &&
                        products.map((p, index) => {
                            return (
                                <div
                                    className=""
                                    onClick={() => redirectToDetail(p.slug)}
                                >
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
                        src={ico_sv1}
                        alt="Service 1"
                    />
                    <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                </div>

                <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
                    <img
                        width={"50px"}
                        height={"50px"}
                        src={ico_sv2}
                        alt="Service 2"
                    />
                    <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                </div>

                <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
                    <img
                        width={"50px"}
                        height={"50px"}
                        src={ico_sv3}
                        alt="Service 3"
                    />
                    <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                </div>

                <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
                    <img
                        width={"50px"}
                        height={"50px"}
                        src={ico_sv4}
                        alt="Service 4"
                    />
                    <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                </div>
            </div>
        </div>
    );
}

export default Home;
