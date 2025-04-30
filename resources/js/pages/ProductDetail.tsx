import { useNavigate, useParams } from "react-router-dom";
import Blog from "../components/Blog";
import "./ProductDetail.scss";
import { useEffect, useState, useCallback } from "react";
import {
    addCommentForProduct,
    deleteCommentOfProduct,
    getCommentForProduct,
    getDetail,
    updateCommentForProduct,
    getRelatedProducts
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
import ReactSwal from "../utils/Swal";
import ico_sv1 from '../assets/ico_sv1.png';
import ico_sv2 from '../assets/ico_sv2.webp';
import ico_sv3 from '../assets/ico_sv3.webp';
import ico_sv4 from '../assets/ico_sv4.png';
import CommentForm from "../components/CommentForm";


const ProductDetail = () => {
    const nav = useNavigate();

    const [showDropdown, setShowDropdown] = useState(false);

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
                },
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
        ],
    };

    const [content, setContent] = useState("");
    const [rating, setRating] = useState(0);
    const [hoverRating, setHoverRating] = useState(0);
    const [images, setImages] = useState<any>([]);
    const [imagePreviews, setImagePreviews] = useState<any>([]);
    const [vouchers, setVouchers] = useState([]);
    const [validVouchers, setValidVouchers] = useState([]);
    const { slug } = useParams();
    const [quantity, setQuantity] = useState(1);
    const [product, setProduct] = useState<any>(null);
    const [productVariant, setProductVariant] = useState({});
    const [selectedSize, setSelectedSize] = useState<number | null>(null);
    const [selectedColor, setSelectedColor] = useState<number | null>(null);
    const [commentList, setCommentList] = useState<any>([]);
    const [showCommentForm, setShowCommentForm] = useState<any>(false);
    const [opacity, setOpacity] = useState<any>(1);
    const [updateComment, setUpdateComment] = useState<any>(false);
    const [relatedProducts, setRelatedProducts] = useState<any[]>([]);

    const voucherListId = "productVoucherList";
    const canScrollVouchers = useScrollable(voucherListId, 4, validVouchers);
    useHorizontalScroll(voucherListId, canScrollVouchers, 0.5);

    const relatedListId = "relatedProductsList";
    const canScrollRelated = useScrollable(relatedListId, 4, relatedProducts);
    useHorizontalScroll(relatedListId, canScrollRelated, 0.5);

    const scrollVouchers = useCallback(
        (direction: "left" | "right") => {
            const container = document.getElementById(voucherListId);
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
        },
        [voucherListId]
    );

    const scrollRelatedProducts = useCallback((direction: 'left' | 'right') => {
        const container = document.getElementById(relatedListId);
        if (!container) return;

        const scrollAmount = 2000;
        const scrollPosition = direction === 'left'
            ? container.scrollLeft - scrollAmount
            : container.scrollLeft + scrollAmount;

        container.scrollTo({
            left: scrollPosition,
            behavior: 'smooth'
        });
    }, []);

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


    const handleSubmitComment = async (e: any) => {
        e.preventDefault();
        try {
            const res = await addCommentForProduct(
                { content: content, rating: rating, "images[]": images },
                slug
            );
            if (res && res.data) {
                toast.success("Thêm comment thành công!", {
                    autoClose: 1000,
                    onClose: () => {
                        window.location.reload();
                    }
                });
            }
        } catch (error: any) {
            toast.error(error.response.data.message);
        }
        console.log({ content, rating, images });
    };

    const getAllCommentsOfProduct = async () => {
        try {
            const res = await getCommentForProduct(slug);
            if (res && res.data) {
                setCommentList(res.data.data);
                console.log(res.data.data);
            }
        } catch (error) { }
    };

    const onDeleteComment = async (commentId: any) => {
        try {
            const result = await ReactSwal.fire({
                title: "Xác nhận hành động",
                text: "Bạn có chắc chắn muốn xóa comment?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Có",
                cancelButtonText: "Không",
            });

            if (result.isConfirmed) {
                const res = await deleteCommentOfProduct(commentId);
                if (res && res.data) {
                    setCommentList(commentList.filter((c: any) => c.id !== commentId))
                    toast.success("Bạn đã xóa thành công comment!");
                }
            } else {

            }
        } catch (error) {
            console.log(error);
        }
    };

    const onUpdateComment = async (content: any, rating: any, images: any) => {
        try {
            const res = await updateCommentForProduct(
                { content: content, rating: rating, "images[]": images, _method: 'PUT' },
                slug, updateComment.id
            );
            if (res && res.data) {
                toast.success("Sửa comment thành công!", {
                    autoClose: 1100,
                    onClose: () => {
                        window.location.reload();
                    }
                });
            }
        } catch (error: any) {
            toast.error(error.response.data.message);
        }
        console.log({ content, rating, images });
    };

    const onCloseForm = () => {
        setShowCommentForm(false);
        setOpacity(1);
    }


    const openUpdateCommentForm = (comment: any) => {
        setOpacity(0.2);
        setUpdateComment(comment);
        setShowCommentForm(true);
    }


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

    const getAvailableSizes = (selectedColorId: number | null) => {
        if (!product?.variants) return [];
        const availableSizes = product.variants
            .filter((v: { color: { id: number; }; }) => !selectedColorId || v.color.id === selectedColorId)
            .map((v: { size: any; }) => v.size);
        return [...new Map(availableSizes.map((s: { id: any; }) => [s.id, s])).values()];
    };

    const getAvailableColors = (selectedSizeId: number | null) => {
        if (!product?.variants) return [];
        const availableColors = product.variants
            .filter((v: { size: { id: number; }; }) => !selectedSizeId || v.size.id === selectedSizeId)
            .map((v: { color: any; }) => v.color);
        return [...new Map(availableColors.map((c: { id: any; }) => [c.id, c])).values()];
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
                const validVoucherList = res.data.data.filter((voucher: any) => {
                    const now = new Date();
                    const endDate = new Date(voucher.end_date);
                    return (
                        voucher.quantity > 0 && // Still has available quantity
                        endDate > now && // Not expired
                        (!voucher.max_uses || voucher.uses < voucher.max_uses) // Has uses remaining
                    );
                });
                setValidVouchers(validVoucherList);
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
            console.error("Lỗi xảy ra:", error);
            const errorMessage =
                error.response?.data?.message ||
                "Có lỗi xảy ra, vui lòng thử lại!";
            toast.error(errorMessage);
        }
    };

    const getRelated = async () => {
        try {
            if (!slug) return;
            const res = await getRelatedProducts(slug);
            if (res && res.data) {
                setRelatedProducts(res.data.data);
            }
        } catch (error) {
            console.error('Error fetching related products:', error);
        }
    };

    useEffect(() => {
        if (slug) {
            getProductDetail();
            getAllCommentsOfProduct();
            getRelated();
        }
    }, [slug]);

    useEffect(() => {
        getAllVouchers();
    }, []);

    return (
        <>
            <div className="product-detail container d-flex gap-5" style={{ opacity: opacity }}>
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
                                            onClick={() =>
                                                setProductVariant(variant)
                                            }
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
                        <span>{validVouchers.length} Mã Giảm Giá</span>
                        <div className="voucher-section position-relative">
                            {canScrollVouchers && (
                                <div
                                    className="scroll-button scroll-left"
                                    onClick={() => scrollVouchers("left")}
                                >
                                    <i className="fas fa-chevron-left"></i>
                                </div>
                            )}

                            <div
                                className="coupon-list gap-2"
                                id={voucherListId}
                                style={{
                                    overflowX: canScrollVouchers
                                        ? "auto"
                                        : "hidden",
                                    cursor: canScrollVouchers ? "grab" : "default",
                                }}
                            >
                                {validVouchers.map((voucher, index) => (
                                    <Voucher key={index} voucher={voucher} />
                                ))}
                            </div>

                            {canScrollVouchers && (
                                <div
                                    className="scroll-button scroll-right"
                                    onClick={() => scrollVouchers("right")}
                                >
                                    <i className="fas fa-chevron-right"></i>
                                </div>
                            )}
                        </div>
                        <div className="line mb-2 mt-2"></div>

                        {/* Size Selection */}
                        <div className="variant-selection mb-3">
                            <span className="d-block mb-2">Kích thước:</span>
                            <div className="size-options d-flex gap-2 flex-wrap">
                                {getUniqueSizes().map((size: any) => {
                                    const isAvailable = getAvailableSizes(selectedColor).some(s => s.id === size.id);
                                    return (
                                        <button
                                            key={size.id}
                                            className={`btn btn-outline-dark rounded-pill px-4 py-2 ${selectedSize === size.id ? "active" : ""
                                                }`}
                                            onClick={() => setSelectedSize(size.id)}
                                            disabled={!isAvailable}
                                        >
                                            {size.name}
                                        </button>
                                    );
                                })}
                            </div>
                        </div>

                        {/* Color Selection */}
                        <div className="variant-selection mb-3">
                            <span className="d-block mb-2">Màu sắc:</span>
                            <div className="color-options d-flex gap-2 flex-wrap">
                                {getUniqueColors().map((color: any) => {
                                    const isAvailable = getAvailableColors(selectedSize).some(c => c.id === color.id);
                                    return (
                                        <button
                                            key={color.id}
                                            className={`btn btn-outline-dark rounded-pill px-4 py-2 d-flex align-items-center gap-2 ${selectedColor === color.id ? "active" : ""
                                                }`}
                                            onClick={() => setSelectedColor(color.id)}
                                            disabled={!isAvailable}
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
                                    );
                                })}
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
                                disabled={!selectedSize || !selectedColor}
                            >
                                <span>Thanh toán online hoặc ship COD</span>
                                <span className="fw-bold">Mua ngay</span>
                            </button>
                            <button
                                className="btn btn-dark col-2"
                                onClick={() => updateCart()}
                                disabled={!selectedSize || !selectedColor}
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

                    {/* Related Products Section */}
                    {relatedProducts.length > 0 && (
                        <div className="related-products mt-5">
                            <h5 className="mb-4">Sản phẩm liên quan</h5>
                            <div className="position-relative">
                                {canScrollRelated && (
                                    <div
                                        className="scroll-button scroll-left"
                                        onClick={() => scrollRelatedProducts('left')}
                                    >
                                        <i className="fas fa-chevron-left"></i>
                                    </div>
                                )}

                                <div
                                    className="product-list"
                                    id={relatedListId}
                                    style={{
                                        overflowX: canScrollRelated ? 'auto' : 'hidden',
                                        cursor: canScrollRelated ? 'grab' : 'default'
                                    }}
                                >
                                    {relatedProducts.map((item) => (
                                        <div
                                            key={item.id}
                                            className="product-item"
                                            onClick={() => nav(`/product/${item.slug}`)}
                                        >
                                            <div className="card h-100">
                                                <img
                                                    src={STORAGE_URL + item.thumb_image}
                                                    className="card-img-top"
                                                    alt={item.name}
                                                />
                                                <div className="card-body">
                                                    <h6 className="card-title text-truncate">{item.name}</h6>
                                                    <p className="card-text text-danger fw-bold">
                                                        {FormatCurrency(item.price_sale != 0 ? item.price_sale : item.price_regular)}đ
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                {canScrollRelated && (
                                    <div
                                        className="scroll-button scroll-right"
                                        onClick={() => scrollRelatedProducts('right')}
                                    >
                                        <i className="fas fa-chevron-right"></i>
                                    </div>
                                )}
                            </div>
                        </div>
                    )}

                    <div className="comment mt-5">
                        <h5>Đánh giá sản phẩm</h5>

                        <form
                            onSubmit={handleSubmitComment}
                            className="mt-3 col-12"
                        >
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
                            <div className="col-lg-12">
                                {commentList.map((comment: any, index: any) => (
                                    <div
                                        key={index}
                                        className="card mb-3 shadow-sm"
                                    >
                                        <div className="card-body">
                                            <div className="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 className="card-title mb-1">
                                                        {comment.user.name}
                                                    </h5>
                                                    <small className="text-muted">
                                                        {comment.user.email}
                                                    </small>
                                                </div>

                                                <div className="d-flex align-items-center">
                                                    <small className="text-muted me-2">
                                                        {FormatDate(
                                                            comment.updated_at
                                                        )}
                                                    </small>
                                                    <div className="dropdowne">
                                                        <button
                                                            className="btn btn-link text-dark p-0"
                                                            type="button"
                                                            data-bs-toggle="dropdowne"
                                                            aria-expanded="false"
                                                            onMouseEnter={() =>
                                                                setShowDropdown(
                                                                    true
                                                                )
                                                            }
                                                            onMouseLeave={() =>
                                                                setShowDropdown(
                                                                    false
                                                                )
                                                            }
                                                        >
                                                            <i className="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul
                                                            className="dropdown-menu dropdown-menu-end dropdowne"
                                                            onMouseLeave={() =>
                                                                setShowDropdown(
                                                                    false
                                                                )
                                                            }
                                                            onMouseEnter={() =>
                                                                setShowDropdown(
                                                                    true
                                                                )
                                                            }
                                                            style={{
                                                                display:
                                                                    showDropdown
                                                                        ? "block"
                                                                        : "none",
                                                            }}
                                                        >
                                                            <li>
                                                                <button className="dropdown-item" onClick={() => openUpdateCommentForm(comment)}>
                                                                    Sửa
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button className="dropdown-item text-danger" onClick={() => onDeleteComment(comment.id)}>
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

                                            {comment.comment_images &&
                                                comment.comment_images.length >
                                                0 && (
                                                    <div className="d-flex flex-wrap gap-2">
                                                        {comment.comment_images.map(
                                                            (
                                                                img: any,
                                                                imgIndex: any
                                                            ) => (
                                                                <img
                                                                    key={imgIndex}
                                                                    src={
                                                                        STORAGE_URL +
                                                                        "/" +
                                                                        img.image
                                                                    }
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
                            <img
                                width="50"
                                height="50"
                                src={ico_sv1}
                                alt="Service 1"
                            />
                            <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                        </div>

                        <div className="service py-2 d-flex gap-3 align-items-center">
                            <img
                                width="50"
                                height="50"
                                src={ico_sv2}
                                alt="Service 2"
                            />
                            <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                        </div>

                        <div className="service py-2 d-flex gap-3 align-items-center">
                            <img
                                width="50"
                                height="50"
                                src={ico_sv3}
                                alt="Service 3"
                            />
                            <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                        </div>

                        <div className="service py-2 d-flex gap-3 align-items-center">
                            <img
                                width="50"
                                height="50"
                                src={ico_sv4}
                                alt="Service 4"
                            />
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
            <CommentForm onCloseForm={onCloseForm} onUpdateComment={onUpdateComment} comment={updateComment} isOpen={showCommentForm} onClose={() => { setShowCommentForm(false); setOpacity(1) }} />
        </>
    );
};

export default ProductDetail;
