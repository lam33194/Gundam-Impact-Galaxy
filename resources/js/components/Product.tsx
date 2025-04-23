import { FormatCurrency } from "../utils/FormatCurrency";
import { STORAGE_URL } from "../utils/constants";
import "./Products.scss";

interface ProductProps {
  p: {
    name: string;
    thumb_image: string;
    price_regular: string;
    price_sale: string;
  }
}

const Product = ({ p }: ProductProps) => {
  return (
    <div className="product d-flex flex-column gap-2">
      <div className="product-image">
        <div
          className="image"
          style={{
            backgroundImage: `url(${STORAGE_URL+p.thumb_image})`,
          }}
        ></div>
      </div>

      <div className="product-info d-flex flex-column">
        <span className="product-name">{p.name}</span>

        {p.price_sale !== "0" ? (
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
        )}
      </div>

      <div className="buy">
        <button className="add-to-cart btn btn-light text-dark me-1" type="button">+</button>
        <button className="payment btn btn-dark text-light" type="button">Mua ngay</button>
      </div>
    </div>
  );
}

export default Product;
