import { useEffect, useState } from "react";
import Product from "../components/Product";
import "./Search.scss";
import { useNavigate, useLocation } from "react-router-dom";
import { getAll, getAllCategories } from "../services/ProductService";
import { FormatCurrency } from "../utils/FormatCurrency";

function Search() {
    const [priceRange, setPriceRange] = useState({ min: "", max: "" });
    const [selectedType, setSelectedType] = useState("");
    const [products, setProducts] = useState<any[]>([]);
    const [categories, setCategories] = useState<any[]>([]);
    const [keyword, setKeyword] = useState("");
    const [activeFilters, setActiveFilters] = useState<{
        search: string;
        min_price: string;
        max_price: string;
        category: string;
    }>({
        search: '',
        min_price: '',
        max_price: '',
        category: ''
    });
    const navigate = useNavigate();
    const location = useLocation();

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

    const getAllProductCategories = async () => {
        try {
            const res = await getAllCategories();
            if (res.data && res.data.data) {
                console.log(res.data.data);
                setCategories(res.data.data);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const redirectToDetail = (slug: any) => {
        navigate("/product/" + slug);
    };

    const handlePriceChange = (e: any) => {
        const { name, value } = e.target;
        const numericValue = value.replace(/\D/g, '');
        setPriceRange({ ...priceRange, [name]: numericValue });
    };

    const handleTypeChange = async (type: string) => {
        setSelectedType(type);
        const updatedParams = {
            ...activeFilters,
            category: type
        };

        try {
            const res = await getAll(updatedParams);
            if (res.data?.data) {
                setProducts(res.data.data);
                setActiveFilters(updatedParams);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const search = async () => {
        try {
            const params = {
                search: keyword
            };
            const res = await getAll(params);
            if (res.data && res.data.data) {
                setProducts(res.data.data);
                setActiveFilters(prev => ({
                    ...prev,
                    search: keyword
                }));
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const applyFilters = async () => {
        const params: any = {};

        if (keyword) params.search = keyword;
        if (priceRange.min) params.min_price = priceRange.min;
        if (priceRange.max) params.max_price = priceRange.max;
        if (selectedType) params.category = selectedType;

        try {
            const res = await getAll(params);
            if (res.data?.data) {
                setProducts(res.data.data);
                setActiveFilters({
                    search: keyword,
                    min_price: priceRange.min,
                    max_price: priceRange.max,
                    category: selectedType
                });
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const removeFilter = async (filterType: 'search' | 'min_price' | 'max_price' | 'category') => {
        let updatedParams: any = { ...activeFilters };

        switch (filterType) {
            case 'search':
                setKeyword('');
                updatedParams.search = '';
                break;
            case 'min_price':
                setPriceRange(prev => ({ ...prev, min: '' }));
                updatedParams.min_price = '';
                break;
            case 'max_price':
                setPriceRange(prev => ({ ...prev, max: '' }));
                updatedParams.max_price = '';
                break;
            case 'category':
                setSelectedType('');
                updatedParams.category = '';
                break;
        }

        setActiveFilters(updatedParams);

        // Remove empty params
        Object.keys(updatedParams).forEach(key => {
            if (!updatedParams[key]) delete updatedParams[key];
        });

        try {
            const res = await getAll(updatedParams);
            if (res.data?.data) {
                setProducts(res.data.data);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    useEffect(() => {
        const performInitialSearch = async () => {
            const state = location.state as { initialKeyword?: string };
            if (state?.initialKeyword) {
                setKeyword(state.initialKeyword);
                try {
                    const params = {
                        search: state.initialKeyword
                    };
                    const res = await getAll(params);
                    if (res.data && res.data.data) {
                        setProducts(res.data.data);
                        setActiveFilters((prev: any) => ({
                            ...prev,
                            search: state.initialKeyword
                        }));
                    }
                } catch (error) {
                    console.log("Detected error:", error);
                }
            } else {
                getAllProducts();
            }
            getAllProductCategories();
        };

        performInitialSearch();
    }, [location.state]);

    return (
        <div className="container mt-5">
            <div className="nav d-flex align-items-center mb-2">
                <a href="" className="text-decoration-none text-dark">
                    Trang chủ
                </a>
                <span className="mx-2">/</span>
                <span className="text-muted">Danh sách sản phẩm</span>
            </div>
            <h2 className="my-4">DANH SÁCH SẢN PHẨM</h2>

            <div className="row">
                <div className="col-lg-3 mt-3">
                    <div className="search-bar mb-5">
                        <p className="text-dark text-bold fw-bold">Tìm Kiếm:</p>
                        <input
                            type="text"
                            className="form-control col-12 mb-2"
                            value={keyword}
                            onChange={(e) => setKeyword(e.target.value)}
                            placeholder="Nhập tên sản phẩm..."
                            style={{ borderRadius: "0.25rem" }}
                        />

                        <button
                            className="btn btn-primary col-12"
                            onClick={search}
                            type="button"
                            style={{ borderRadius: "0" }}
                        >
                            Tìm kiếm
                        </button>
                    </div>
                    <div className="card p-3 shadow-sm">
                        <span className="fw-bold mb-3">Mức Giá:</span>
                        <div className="d-flex mb-3">
                            <input
                                type="text"
                                name="min"
                                className="form-control me-2"
                                placeholder="Từ"
                                value={priceRange.min ? FormatCurrency(priceRange.min) : ''}
                                onChange={handlePriceChange}
                            />
                            <input
                                type="text"
                                name="max"
                                className="form-control"
                                placeholder="Đến"
                                value={priceRange.max ? FormatCurrency(priceRange.max) : ''}
                                onChange={handlePriceChange}
                            />
                        </div>
                        <button
                            className="btn btn-dark mb-4 w-100"
                            onClick={applyFilters}
                        >
                            Áp Dụng Giá
                        </button>

                        <span className="fw-bold mb-3">Loại:</span>
                        {categories.map((c, index) => (
                            <div className="form-check mb-2" key={index}>
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    checked={selectedType === c.slug}
                                    onChange={() => handleTypeChange(c.slug)}
                                />
                                <label className="form-check-label">
                                    {c.name}
                                </label>
                            </div>
                        ))}
                        <button
                            className="btn btn-primary w-100 mb-4"
                            onClick={applyFilters}
                        >
                            Áp dụng bộ lọc
                        </button>
                    </div>
                </div>

                <div className="col-lg-8 offset-lg-1 mt-3">
                    <div className="active-filters d-flex flex-wrap gap-2 mb-3">
                        {activeFilters.search && (
                            <span className="badge bg-primary d-flex align-items-center">
                                Tên: {activeFilters.search}
                                <button
                                    className="btn-close btn-close-white ms-2"
                                    onClick={() => removeFilter('search')}
                                    style={{ fontSize: '0.65em' }}
                                ></button>
                            </span>
                        )}
                        {activeFilters.min_price && (
                            <span className="badge bg-primary d-flex align-items-center">
                                Từ: {FormatCurrency(activeFilters.min_price)}đ
                                <button
                                    className="btn-close btn-close-white ms-2"
                                    onClick={() => removeFilter('min_price')}
                                    style={{ fontSize: '0.65em' }}
                                ></button>
                            </span>
                        )}
                        {activeFilters.max_price && (
                            <span className="badge bg-primary d-flex align-items-center">
                                Đến: {FormatCurrency(activeFilters.max_price)}đ
                                <button
                                    className="btn-close btn-close-white ms-2"
                                    onClick={() => removeFilter('max_price')}
                                    style={{ fontSize: '0.65em' }}
                                ></button>
                            </span>
                        )}
                        {activeFilters.category && (
                            <span className="badge bg-primary d-flex align-items-center">
                                Loại: {categories.find(c => c.slug === activeFilters.category)?.name}
                                <button
                                    className="btn-close btn-close-white ms-2"
                                    onClick={() => removeFilter('category')}
                                    style={{ fontSize: '0.65em' }}
                                ></button>
                            </span>
                        )}
                    </div>
                    {products.length === 0 ? (
                        <div className="mt-3 fw-semibold fst-italic">
                            <span>Không tìm thấy Sản phẩm thỏa mãn!</span>
                        </div>
                    ) : ''}
                    <ul className="list-group mt-3">
                        {products &&
                            products.map((p, index) => (
                                <li
                                    key={index}
                                    className="list-group-item list-group-item-action"
                                    onClick={() => redirectToDetail(p.slug)}
                                >
                                    <Product p={p} />
                                </li>
                            ))}
                    </ul>
                </div>
            </div>
        </div>
    );
}
export default Search;
