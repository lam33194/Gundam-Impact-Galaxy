import { useNavigate, useParams } from "react-router-dom";
import Blog from "../components/Blog";
import "./ProductDetail.scss";
import { useEffect, useState, useCallback } from "react";
import {
    addCommentForProduct,
    getCommentForProduct,
    getDetail,
} from "../services/ProductService";
import { addToCart } from "../services/CartService";
import { toast } from "react-toastify";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import Slider from "react-slick";
import { FormatCurrency } from "../utils/FormatCurrency";
import { useHorizontalScroll } from "../hooks/useHorizontalScroll";
import { useScrollable } from "../hooks/useScrollable";
import Voucher from "../components/Voucher";
import { getVouchers } from "../services/VoucherService";
import { FormatDate } from "../utils/FormatDate";
import { STORAGE_URL } from "../utils/constants";

const ProductDetail = () => {
    const nav = useNavigate();

    const settings = {
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    };

    const [content, setContent] = useState("");
    const [rating, setRating] = useState(0);
    const [hoverRating, setHoverRating] = useState(0);
    const [images, setImages] = useState<any>([]);
    const [imagePreviews, setImagePreviews] = useState<any>([]);
    const [vouchers, setVouchers] = useState([]);
    const { slug } = useParams();
    const [quantity, setQuantity] = useState(1);
    const [product, setProduct] = useState<any>(null);
    const [productVariant, setProductVariant] = useState({});
    const [selectedSize, setSelectedSize] = useState<number | null>(null);
    const [selectedColor, setSelectedColor] = useState<number | null>(null);
    const [commentList, setCommentList] = useState<any>([]);

    const voucherListId = 'productVoucherList';
    const canScrollVouchers = useScrollable(voucherListId, 4, vouchers);
    useHorizontalScroll(voucherListId, canScrollVouchers, 0.5);

    const scrollVouchers = useCallback((direction: 'left' | 'right') => {
        const container = document.getElementById(voucherListId);
        if (!container) return;

        const scrollAmount = 400;
        const scrollPosition = direction === 'left'
            ? container.scrollLeft - scrollAmount
            : container.scrollLeft + scrollAmount;

        container.scrollTo({
            left: scrollPosition,
            behavior: 'smooth'
        });
    }, [voucherListId]);

    const handleImageUpload = (e: any) => {
        const files = Array.from(e.target.files);
        if (files.length + images.length > 5) {
            alert("Tối đa 5 ảnh được phép tải lên");
            return;
        }

        const newImages = [...images, ...files];
        setImages(newImages.slice(0, 5));

        // Tạo preview
        const previews = files.map((file: any) => URL.createObjectURL(file));
        setImagePreviews([...imagePreviews, ...previews].slice(0, 5));
    };

    const removeImage = (index: any) => {
        const newImages = [...images];
        newImages.splice(index, 1);
        setImages(newImages);

        const newPreviews = [...imagePreviews];
        URL.revokeObjectURL(newPreviews[index]);
        newPreviews.splice(index, 1);
        setImagePreviews(newPreviews);
    };

    const handleSubmit = (e: any) => {
        e.preventDefault();
        console.log({ content, rating, images });
    };

    const handleSubmitComment = async (e: any) => {
        e.preventDefault();
        try {
            const res = await addCommentForProduct(
                { "content": content, "rating": rating, "images[]": images },
                slug
            );
            if (res && res.data) {
                toast.success("Thêm comment thành công!");
            }
        } catch (error : any) {
            toast.error(error.response.data.message);
            // console.log(error.response);
        }
        console.log({ content, rating, images });
    };

    const getAllCommentsOfProduct = async () => {
        try {
            const res = await getCommentForProduct(slug);
            if (res && res.data) {
                setCommentList(res.data.data);
            }
        } catch (error) { }
    };

    const getUniqueSizes = () => {
        if (!product?.variants) return [];
        const sizes = product.variants.map((v: { size: any }) => v.size);
        return [...new Map(sizes.map((s: { id: any }) => [s.id, s])).values()];
    };

    const getUniqueColors = () => {
        if (!product?.variants) return [];
        const colors = product.variants.map((v: { color: any }) => v.color);
        return [...new Map(colors.map((c: { id: any }) => [c.id, c])).values()];
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

    const updateCart = async (index?: any) => {
        try {
            if (!selectedSize || !selectedColor) {
                toast.error("Vui lòng chọn kích thước và màu sắc!");
                return;
            }

            const selectedVariant = product.variants.find(
                (v: { size: { id: number }; color: { id: number } }) =>
                    v.size.id === selectedSize && v.color.id === selectedColor
            );

            if (!selectedVariant) {
                toast.error("Phiên bản sản phẩm không tồn tại!");
                return;
            }

            const res = await addToCart({
                product_variant_id: selectedVariant.id,
                quantity,
            });

            if (res?.data) {
                toast.success("Đã thêm vào giỏ hàng!");
            }
            if (index === -1) {
                nav("/cart");
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
            getAllCommentsOfProduct();
        }
    }, [slug]);

    useEffect(() => {
        if (product?.variants && product.variants.length > 0) {
            const firstVariant = product.variants[0];
            setSelectedSize(firstVariant.size.id);
            setSelectedColor(firstVariant.color.id);
        }
    }, [product]);

    useEffect(() => {
        getAllVouchers();
    }, []);

    return (
        <div className="product-detail container d-flex">
            <div className="detail row col-9 gap-4">
                <div className="image col-lg-5">
                    <div
                        className="img"
                        style={{
                            // backgroundImage: `url(${"https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390"})`,
                            backgroundImage: `url(${STORAGE_URL + product?.thumb_image})`,
                        }}
                    ></div>

                    <div className="col-lg-10">
                        <Slider {...settings}>
                            {product?.variants.map((variant: any) => (
                                <div
                                    key={variant.id}
                                    className="product-variant"
                                >
                                    <img
                                        src={
                                            variant.image == null  
                                            ? "https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390"
                                            : STORAGE_URL + variant.image
                                        }
                                        alt={`Variant ${variant.id}`}
                                        className="w-100 h-100 object-fit-cover"
                                        onClick={() => setProductVariant(variant)}
                                    />

                                </div>
                            ))}

                            {product?.galleries.map((gallery: any) => (
                                <div
                                    key={gallery.id}
                                    className="product-variant"
                                >
                                    <img
                                        src={
                                            gallery.image == null  
                                            ? "https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390"
                                            : STORAGE_URL + gallery.image
                                        }
                                        alt={`Product gallery ${gallery.id}`}
                                        className="w-100 h-100 object-fit-cover"
                                        // onClick={() => setProductVariant(variant)}
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
                            Mã sản phẩm:&nbsp;
                            {product !== null ? product.sku : ""}
                        </span>
                    </div>
                    <span className="price">
                        {product !== null 
                            ? FormatCurrency(product.price_sale != 0 ? product.price_sale : product.price_regular) 
                            : ""}
                        đ
                    </span>
                    {/* (Đánh giá: {product?.average_rating}) */}
                    {/* (Lượt đánh giá {product?.total_ratings}) */}
                    <div className="line mb-2 mt-1"></div>
                    <span>{vouchers.length} Mã Giảm Giá</span>
                    <div className="voucher-section position-relative">
                        {canScrollVouchers && (
                            <div className="scroll-button scroll-left" onClick={() => scrollVouchers('left')}>
                                <i className="fas fa-chevron-left"></i>
                            </div>
                        )}

                        <div
                            className="coupon-list gap-2"
                            id={voucherListId}
                            style={{
                                overflowX: canScrollVouchers ? 'auto' : 'hidden',
                                cursor: canScrollVouchers ? 'grab' : 'default'
                            }}
                        >
                            {vouchers.map((voucher, index) => (
                                <Voucher key={index} voucher={voucher} />
                            ))}
                        </div>

                        {canScrollVouchers && (
                            <div className="scroll-button scroll-right" onClick={() => scrollVouchers('right')}>
                                <i className="fas fa-chevron-right"></i>
                            </div>
                        )}
                    </div>
                    <div className="line mb-2 mt-2"></div>

                    {/* Size Selection */}
                    <div className="variant-selection mb-3">
                        <span className="d-block mb-2">Kích thước:</span>
                        <div className="size-options d-flex gap-2 flex-wrap">
                            {getUniqueSizes().map((size: any) => (
                                <button
                                    key={size.id}
                                    className={`btn btn-outline-dark rounded-pill px-4 py-2 ${selectedSize === size.id ? "active" : ""
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
                            {getUniqueColors().map((color: any) => (
                                <button
                                    key={color.id}
                                    className={`btn btn-outline-dark rounded-pill px-4 py-2 d-flex align-items-center gap-2 ${selectedColor === color.id
                                        ? "active"
                                        : ""
                                        }`}
                                    onClick={() => setSelectedColor(color.id)}
                                >
                                    <span
                                        className="color-preview"
                                        style={{
                                            width: "20px",
                                            height: "20px",
                                            borderRadius: "50%",
                                            backgroundColor: color.code,
                                            border: "1px solid #dee2e6",
                                        }}
                                    ></span>
                                    {color.name}
                                </button>
                            ))}
                        </div>
                    </div>
                    {/* Hiển thị tồn kho ? */}
                    {/* {product?.variants.forEach(element => {
                        console.log(element.quantity);
                    })} */}
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
                        <button
                            className="btn btn-dark col-10 d-flex flex-column"
                            onClick={() => updateCart(-1)}
                        >
                            <span>Thanh toán online hoặc ship COD</span>
                            <span className="fw-bold">Mua ngay</span>
                        </button>
                        <button
                            className="btn btn-dark col-2"
                            onClick={() => updateCart()}
                        >
                            <i className="fa-solid fa-cart-shopping"></i>
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

                <div className="comment mt-5">
                    <h5>Đánh giá sản phẩm</h5>

                    <form onSubmit={handleSubmitComment} className="mt-3 col-11">
                        <div className="form-group mb-3">
                            <div className="star-rating">
                                {[1, 2, 3, 4, 5].map((star) => (
                                    <button
                                        key={star}
                                        type="button"
                                        className="btn p-1"
                                        onClick={() => setRating(star)}
                                        onMouseEnter={() =>
                                            setHoverRating(star)
                                        }
                                        onMouseLeave={() => setHoverRating(0)}
                                    >
                                        <i
                                            className="fa-solid fa-star"
                                            style={{
                                                color:
                                                    star <=
                                                        (hoverRating || rating)
                                                        ? "#ffc107"
                                                        : "#ddd",
                                            }}
                                        ></i>
                                    </button>
                                ))}
                            </div>
                        </div>
                        <div className="form-group mb-3">
                            <textarea
                                rows={4}
                                value={content}
                                onChange={(e) => setContent(e.target.value)}
                                placeholder="Nội dung bình luận"
                                className="form-control"
                            />
                        </div>

                        <div className="form-group mb-3">
                            <label>Tải lên ảnh (tối đa 5 ảnh)</label>
                            <div className="d-flex flex-wrap gap-2 mb-2">
                                {imagePreviews.map(
                                    (preview: any, index: any) => (
                                        <div
                                            key={index}
                                            className="position-relative"
                                            style={{
                                                width: "120px",
                                                height: "120px",
                                            }}
                                        >
                                            <img
                                                src={preview}
                                                alt={`Preview ${index}`}
                                                className="img-thumbnail h-100 w-100 object-fit-cover"
                                            />
                                            <button
                                                type="button"
                                                className="btn btn-sm btn-secondary position-absolute top-0 end-0"
                                                onClick={() =>
                                                    removeImage(index)
                                                }
                                            >
                                                <i className="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    )
                                )}
                            </div>

                            <label className="btn btn-outline-secondary">
                                Chọn ảnh
                                <input
                                    type="file"
                                    multiple
                                    accept="image/*"
                                    onChange={handleImageUpload}
                                    className="d-none"
                                />
                            </label>
                            <small className="d-block text-muted">
                                Đã chọn: {images.length}/5 ảnh
                            </small>
                        </div>

                        <button
                            type="submit"
                            className="btn btn-primary"
                            disabled={
                                !content && !rating && images.length === 0
                            }
                        >
                            Gửi bình luận
                        </button>
                    </form>
                </div>
                <div className="container mt-4">
                    <h3 className="text-center mb-4">Danh sách Bình luận ({product?.total_comments})</h3>
                    <div className="row justify-content-start">
                        <div className="col-lg-11">
                            {commentList.map((comment: any, index: any) => (
                                <div
                                    key={index}
                                    className="card mb-3 shadow-sm"
                                >
                                    <div className="card-body">
                                        <div className="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 className="card-title mb-1">
                                                    {comment.userName}
                                                </h5>
                                                <small className="text-muted">
                                                    {comment.userEmail}
                                                </small>
                                            </div>

                                            <div className="d-flex align-items-center">
                                                <small className="text-muted me-2">
                                                    {FormatDate(comment.updated_at)}
                                                </small>
                                                <div className="dropdown">
                                                    <button
                                                        className="btn btn-link text-dark p-0"
                                                        type="button"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                    >
                                                        <i className="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul className="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <button className="dropdown-item">
                                                                Sửa
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button className="dropdown-item text-danger">
                                                                Xóa
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>


                                        {comment.rating && (
                                            <div className="mb-2">
                                                {[...Array(5)].map((_, i) => (
                                                    <span
                                                        key={i}
                                                        className={
                                                            i < comment.rating
                                                                ? "text-warning"
                                                                : "text-secondary"
                                                        }
                                                    >
                                                        ★
                                                    </span>
                                                ))}
                                                <span className="ms-2 small text-muted">
                                                    ({comment.rating}/5)
                                                </span>
                                            </div>
                                        )}


                                        <p className="card-text mb-3">
                                            {comment.content}
                                        </p>


                                        {comment.images &&
                                            comment.images.length > 0 && (
                                                <div className="d-flex flex-wrap gap-2">
                                                    {comment.images.map(
                                                        (img: any, imgIndex: any) => (
                                                            <img
                                                                key={imgIndex}
                                                                src={img}
                                                                alt={`Ảnh ${imgIndex + 1
                                                                    }`}
                                                                className="img-thumbnail"
                                                                style={{
                                                                    width: "80px",
                                                                    height: "80px",
                                                                    objectFit:
                                                                        "cover",
                                                                }}
                                                            />
                                                        )
                                                    )}
                                                </div>
                                            )}
                                    </div>
                                </div>
                            ))}
                        </div>
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
