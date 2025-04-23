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
            backgroundImage: `url(${p.thumb_image != null 
              ? STORAGE_URL+p.thumb_image 
              : 'https://bizweb.dktcdn.net/thumb/large/100/456/060/products/adee7807-6673-4ecc-a99a-4f111563836f-1706888678630.jpg?v=1732090977817'})`,
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
