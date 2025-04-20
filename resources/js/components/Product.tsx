import { FormatCurrency } from "../utils/FormatCurrency";
import "./Products.scss";
const Product = (p?: any) => {
  return (
    <div className="product d-flex flex-column gap-2">
      <div className="product-image">
      <div
        className="image"
        style={{
          backgroundImage:
            "url(https://bizweb.dktcdn.net/thumb/large/100/456/060/products/adee7807-6673-4ecc-a99a-4f111563836f-1706888678630.jpg?v=1732090977817)",
            // "url(http://127.0.0.1:8000/storage/"+p.thumb_image+")",
        }}
      ></div>
      </div>

      <div className="product-info d-flex flex-column">
        <span className="product-name">
          {/* Mô hình PG 1/60 Zeta Gundam - Mô hình Gundam chính hãng Bandai Nhật
          Bản */}
          {p.name}
        </span>
        {/* <span className="product-price">
          { 4.500.000đ }
          {FormatCurrency(p.price_sale)}đ
        </span> */}

        {p.price_sale != 0 ? (
          <div>
            <span>
              <s>{FormatCurrency(p.price_regular)}đ</s>
            </span>
            &ensp;
            <span className="product-price">
              {FormatCurrency(p.price_sale)}đ
            </span>
          </div>
        ) : (
          <span className="product-price">
            {FormatCurrency(p.price_regular)}đ
          </span>
        )} <br />
      </div>

      <div className="buy">
      <button className="add-to-cart btn btn-light text-dark me-1" type="button">+</button>
        <button className="payment btn btn-dark text-light" type="button">Mua ngay</button>

      </div>
    </div>
  );
}

export default Product;
