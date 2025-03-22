import { Carousel } from "react-bootstrap";
import "./Home.scss";
import Product from "../components/Product";
import Coupon from "../components/Coupon";
import Blog from "../components/Blog";

function Home() {
  return (
    <div className="home-container container flex-column d-flex gap-5">
      <Carousel prevLabel="Previous" nextLabel="Next">
        <Carousel.Item>
          <img
            className="d-block w-100"
            src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/slider_2.jpg?1740630578329" // Thay thế bằng URL hình ảnh sản phẩm
            alt="First slide"
          />
        </Carousel.Item>

        <Carousel.Item>
          <img
            className="d-block w-100"
            src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/slider_2.jpg?1740630578329"
            alt="Second slide"
          />
        </Carousel.Item>
      </Carousel>

      <div className="best-seller">
        <span className="fw-bold text-uppercase">Sản phẩm bán chạy</span>
        <div className="product-list">
          <Product />
          <Product />
          <Product />
          <Product />
          <Product />
        </div>
      </div>
      <div className="banner row ">
        <img
          className="col-6"
          height={"280px"}
          src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/imgbanner1.jpg?1740630578329"
        />
        <img
          className="col-6"
          height={"280px"}
          src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/imgbanner2.jpg?1740630578329"
        />
      </div>
      <div className="coupon-list gap-2">
        <Coupon />
        <Coupon />
        <Coupon />
      </div>

      <div className="blog-list">
        <span className="fw-bold text-uppercase">BLOG TIN TỨC</span>
        <div className="list row">
          <div className="left col-6">
            <Blog display={"column"} />
          </div>
          <div className="right d-flex flex-column gap-2 col-6">
            <Blog display={"row"} />
            <Blog display={"row"} />
            <Blog display={"row"} />
          </div>
        </div>
      </div>
      <div className="best-seller">
        <span className="fw-bold text-uppercase">CÓ THỂ BẠN THÍCH</span>
        <div className="product-list">
          <Product />
          <Product />
          <Product />
          <Product />
          <Product />
        </div>
      </div>
      <div className="service-info row">
        <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
          <img
            width={"50px"}
            height={"50px"}
            src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv1.png?1740630578329"
          />
          <span className="fw-bold">Dịch vụ đóng gói riêng</span>
        </div>

        <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
          <img
            width={"50px"}
            height={"50px"}
            src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv2.png?1740630578329"
          />
          <span className="fw-bold">Dịch vụ đóng gói riêng</span>
        </div>

        <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
          <img
            width={"50px"}
            height={"50px"}
            src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv3.png?1740630578329"
          />
          <span className="fw-bold">Dịch vụ đóng gói riêng</span>
        </div>

        <div className="service p-2 col-lg-3 col-sm-12 d-flex gap-3 align-items-center">
          <img
            width={"50px"}
            height={"50px"}
            src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv4.png?1740630578329"
          />
          <span className="fw-bold">Dịch vụ đóng gói riêng</span>
        </div>
      </div>
    </div>
  );
}

export default Home;
