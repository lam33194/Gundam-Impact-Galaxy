import { useEffect, useState } from "react";
import Product from "../components/Product";
import "./Search.scss";
import { useNavigate } from "react-router-dom";
import { getAll } from "../services/ProductService";

function Search() {
    const [products, setProducts] = useState([]);
    const navigate = useNavigate();

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
        navigate("/product/" + slug);
    };

    useEffect(() => {
        getAllProducts();
    }, []);
    return (
        <div className="container mt-5">
            <h1 className="text-center mb-4">Tìm kiếm sản phẩm</h1>

            {/* Thanh tìm kiếm */}
            <div className="input-group mb-4">
                <input
                    type="text"
                    className="form-control"
                    placeholder="Nhập tên sản phẩm..."
                />
                <button className="btn btn-primary" type="button">
                    Search
                </button>
            </div>

            <h5 className="mt-4">DANH SÁCH SẢN PHẨM</h5>
            <ul className="list-group mt-3">
                {products &&
                    products.map((p, index) => {
                        return (
                            <div
                                className="prod"
                                onClick={() => redirectToDetail(p.slug)}
                            >
                                <Product key={index} p={p} />
                            </div>
                        );
                    })}
            </ul>
        </div>
    );
}
export default Search;
