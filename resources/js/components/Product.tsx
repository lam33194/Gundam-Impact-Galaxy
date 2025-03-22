import "./Products.scss";
function Product() {
  return (
    <div className="product d-flex flex-column gap-2">
      <div className="product-image">
      <div
        className="image"
        style={{
          backgroundImage:
            "url(https://bizweb.dktcdn.net/thumb/large/100/456/060/products/adee7807-6673-4ecc-a99a-4f111563836f-1706888678630.jpg?v=1732090977817)",
        }}
      ></div>
      </div>

      <div className="product-info d-flex flex-column">
        <span className="product-name">
          Mô hình PG 1/60 Zeta Gundam - Mô hình Gundam chính hãng Bandai Nhật
          Bản
        </span>
        <span className="product-price">4.500.000đ</span>
      </div>

      <div className="buy">
      <button className="add-to-cart btn btn-light text-dark me-1" type="button">+</button>
        <button className="payment btn btn-dark text-light" type="button">Mua ngay</button>

      </div>
    </div>
  );
}

export default Product;
