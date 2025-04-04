import { useNavigate, useParams } from "react-router-dom";
import Blog from "../components/Blog";
import "./ProductDetail.scss";
import { useEffect, useState } from "react";
import { getDetail } from "../services/ProductService";
import { addToCart } from "../services/CartService";
import { toast } from "react-toastify";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import Slider from "react-slick";

const ProductDetail = () => {
    const productVariants = [
        { id: 1, imageUrl: "https://example.com/image1.jpg" },
        { id: 2, imageUrl: "https://example.com/image2.jpg" },
        { id: 3, imageUrl: "https://example.com/image3.jpg" },
    ];

    const nav = useNavigate();

    const settings = {
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 2,
        slidesToScroll: 1,
    };

    const { slug } = useParams();
    const [quantity, setQuantity] = useState(1);
    const [product, setProduct] = useState<any>(null);
    const [productVariant, setProductVariant] = useState({});

    const getProductDetail = async () => {
        try {
            const res = await getDetail(slug);
            console.log(res);
            if (res && res.data) {
                setProduct(res.data);
                console.log(res.data.variants[0].id);
            }
        } catch (error) {
            console.log(error);
        }
    };

    const updateCart = async (index ?: any) => {
        try {
            const res = await addToCart({
                product_variant_id: product!.variants[0].id,
                quantity: quantity,
            });
            if (res && res.data) {
                if (index === -1){
                    nav('/cart')
                }
                toast.success("Đã thêm vào giỏ hàng!");
                console.log(res.data);
            }
        } catch (error) {
            console.log(error);
        }
    };

    useEffect(() => {
        if (slug) {
            getProductDetail();
        }
    }, [slug]);

    return (
        <div className="product-detail container d-flex">
            <div className="detail row col-9 gap-4">
                <div className="image col-lg-5">
                    <div
                        className="img"
                        // style={{
                        //     backgroundImage: `url(${
                        //         productVariant!.image ||
                        //         "https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390"
                        //     })`,
                        // }}
                        style={{
                            backgroundImage: `url(${
                                "https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390"
                            })`,
                        }}
                    ></div>

                    <div className="col-lg-10">
                        <Slider {...settings}>
                            {product?.variants.map((variant: any) => (
                                <div key={variant.id} className="product-variant">
                                    <img
                                    className="product-variant"
                                        src={
                                            variant.imageu ||
                                            "https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390"
                                        }
                                        alt={`Slide ${variant.id}`}
                                        style={{
                                            width: "120px",
                                            height: "120px",
                                        }}
                                        onClick={() =>
                                            setProductVariant(variant)
                                        }
                                    />
                                
                                </div>
                            ))}
                        </Slider>
                    </div>
                </div>

                <div className="info d-flex flex-column gap-1 col-lg-6 col-sm-12">
                    <h5 className="fw-bold mb-0">
                        {product !== null ? product.name : ""}
                        {/* Mô hình tàu One Piece (15cm) - Baratie (Sanji) - Mô hình
                        chính hãng Bandai Nhật Bản */}
                    </h5>
                    <div className="d-flex gap-4">
                        <span>
                            Thương hiệu:{" "}
                            <strong>
                                {/* BANDAI */}
                                {product !== null ? product.category.name : ""}
                            </strong>
                        </span>
                        <span>
                            Mã sản phẩm: {product !== null ? product.sku : ""}
                        </span>
                    </div>
                    <span className="price">
                        {product !== null ? product.price_regular : ""}đ
                    </span>
                    <div className="line mb-2 mt-1"></div>
                    <span>5 Mã Giảm Giá</span>
                    <div className="coupon-lists d-flex gap-2">
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                    </div>
                    <div className="line mb-2 mt-2"></div>
                    <span>Số lượng:</span>
                    <div
                        className="input-group input-group-sm quantity-selector"
                        style={{ width: "100px" }}
                    >
                        <button
                            className="btn btn-outline-dark"
                            type="button"
                            id="button-minus"
                            onClick={() =>
                                quantity > 0
                                    ? setQuantity(quantity - 1)
                                    : setQuantity(0)
                            }
                        >
                            <i className="bi bi-dash"></i>
                        </button>

                        <input
                            type="text"
                            className="fw-bold form-control text-center"
                            value={quantity}
                            aria-label="Quantity"
                            min="1"
                        />

                        <button
                            className="btn btn-outline-dark"
                            type="button"
                            id="button-plus"
                            onClick={() => setQuantity(quantity + 1)}
                        >
                            <i className="bi bi-plus"></i>
                        </button>
                    </div>

                    <div className="d-flex gap-2 pay my-2">
                        <button className="btn btn-dark col-10 d-flex flex-column">
                            <span>Thanh toán online hoặc ship COD</span>
                            <span className="fw-bold"  onClick={() => updateCart(-1)}>Mua ngay</span>
                        </button>
                        <button className="btn btn-dark col-2">
                            <i
                                className="fa-solid fa-cart-shopping"
                                onClick={() => updateCart()}
                            ></i>
                        </button>
                    </div>

                    <div className="share">
                        <p className="mb-1">Chia sẻ ngay:</p>
                        <button className="me-1 facebook btn btn-sm text-light">
                            <i className="fa-brands fa-facebook-f"></i> Chia sẻ
                        </button>

                        <button className="me-1 pinterest btn btn-sm text-light">
                            <i className="fa-brands fa-pinterest-p"></i> Chia sẻ
                        </button>

                        <button className="twitter btn btn-sm btn-primary text-light">
                            <i className="fa-brands fa-twitter"></i> Chia sẻ
                        </button>
                    </div>
                </div>
            </div>

            <div className="blog-article d-flex flex-column col-3">
                <span className="text-uppercase fw-bold">
                    Ưu đãi thành viên{" "}
                </span>
                <div className="service-info d-flex flex-column mb-4 gap-1">
                    <div className="service py-2 d-flex gap-3 align-items-center">
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv1.png?1740630578329" />
                        <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                    </div>

                    <div className="service py-2 d-flex gap-3 align-items-center">
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv2.png?1740630578329" />
                        <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                    </div>

                    <div className="service py-2 d-flex gap-3 align-items-center">
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv3.png?1740630578329" />
                        <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                    </div>

                    <div className="service py-2 d-flex gap-3 align-items-center">
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv4.png?1740630578329" />
                        <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                    </div>
                </div>

                <span className="fw-bold text-uppercase mb-1">
                    Tin mới nhất
                </span>
                <div className="blog-list d-flex flex-column gap-4">
                    <Blog display={"column"} backgroundSize={"contain"} />
                    <Blog display={"column"} backgroundSize={"contain"} />
                    <Blog display={"column"} backgroundSize={"contain"} />
                </div>
            </div>
        </div>
    );
};

export default ProductDetail;
