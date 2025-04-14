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
    const [selectedSize, setSelectedSize] = useState<number | null>(null);
    const [selectedColor, setSelectedColor] = useState<number | null>(null);

    const getUniqueSizes = () => {
        if (!product?.variants) return [];
        const sizes = product.variants.map((v: { size: any; }) => v.size);
        return [...new Map(sizes.map((s: { id: any; }) => [s.id, s])).values()];
    };

    const getUniqueColors = () => {
        if (!product?.variants) return [];
        const colors = product.variants.map((v: { color: any; }) => v.color);
        return [...new Map(colors.map((c: { id: any; }) => [c.id, c])).values()];
    };

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

    const updateCart = async () => {
        try {
            if (!selectedSize || !selectedColor) {
                toast.error("Vui lòng chọn kích thước và màu sắc!");
                return;
            }

            const selectedVariant = product.variants.find(
                (v: { size: { id: number; }; color: { id: number; }; }) => v.size.id === selectedSize && v.color.id === selectedColor
            );

            if (!selectedVariant) {
                toast.error("Phiên bản sản phẩm không tồn tại!");
                return;
            }

            const res = await addToCart({
                product_variant_id: selectedVariant.id,
                quantity
            });

            if (res?.data) {
                toast.success("Đã thêm vào giỏ hàng!");
            }
        } catch (error: any) {
            console.error('Lỗi xảy ra:', error);
            const errorMessage = error.response?.data?.message || "Có lỗi xảy ra, vui lòng thử lại!";
            toast.error(errorMessage);
        }
    };

    useEffect(() => {
        if (slug) {
            getProductDetail();
        }
    }, [slug]);

    useEffect(() => {
        if (product?.variants && product.variants.length > 0) {
            const firstVariant = product.variants[0];
            setSelectedSize(firstVariant.size.id);
            setSelectedColor(firstVariant.color.id);
        }
    }, [product]);

    return (
        <div className="product-detail container d-flex">
            <div className="detail row col-9 gap-4">
                <div className="image col-lg-5">
                    <div
                        className="img"
                        style={{
                            backgroundImage: `url(${"https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390"
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
                    </h5>
                    <div className="d-flex gap-4">
                        <span>
                            Thương hiệu:&nbsp;
                            <strong>
                                {product !== null ? product.category.name : ""}
                            </strong>
                        </span>
                        <span>
                            Mã sản phẩm:&nbsp;{product !== null ? product.sku : ""}
                        </span>
                    </div>
                    <span className="price">
                        {product !== null ? product.price_sale : ""}đ
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

                    {/* Size Selection */}
                    <div className="variant-selection mb-3">
                        <span className="d-block mb-2">Kích thước:</span>
                        <div className="size-options d-flex gap-2 flex-wrap">
                            {getUniqueSizes().map(size => (
                                <button
                                    key={size.id}
                                    className={`btn btn-outline-dark rounded-pill px-4 py-2 ${selectedSize === size.id ? 'active' : ''
                                        }`}
                                    onClick={() => setSelectedSize(size.id)}
                                >
                                    {size.name}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Color Selection */}
                    <div className="variant-selection mb-3">
                        <span className="d-block mb-2">Màu sắc:</span>
                        <div className="color-options d-flex gap-2 flex-wrap">
                            {getUniqueColors().map(color => (
                                <button
                                    key={color.id}
                                    className={`btn btn-outline-dark rounded-pill px-4 py-2 d-flex align-items-center gap-2 ${selectedColor === color.id ? 'active' : ''
                                        }`}
                                    onClick={() => setSelectedColor(color.id)}
                                >
                                    <span
                                        className="color-preview"
                                        style={{
                                            width: '20px',
                                            height: '20px',
                                            borderRadius: '50%',
                                            backgroundColor: color.code,
                                            border: '1px solid #dee2e6'
                                        }}
                                    ></span>
                                    {color.name}
                                </button>
                            ))}
                        </div>
                    </div>

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
                            readOnly
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
                            <span className="fw-bold" onClick={() => {
                                nav("/cart");
                                updateCart();
                            }}>Mua ngay</span>
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
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/1004041/assets/ico_sv3.png?1743759267039" />
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
