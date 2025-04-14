import React from 'react';
import { Carousel } from 'react-bootstrap';

function Slideshow() {
  return (
    <Carousel
      prevLabel="Previous"
      nextLabel="Next"
    >
      <Carousel.Item>
        <img
          className="d-block w-100"
          src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/slider_2.jpg?1740630578329" // Thay thế bằng URL hình ảnh sản phẩm
          alt="First slide"
        />
        {/* <Carousel.Caption>
          <h3>Ultimate Gift</h3>
          <p>Order Natra + 4 màu bất kỳ</p>
        </Carousel.Caption> */}
      </Carousel.Item>

      {/* <Carousel.Item>
        <img
          className="d-block w-100"
          src="image-url-2.jpg" 
          alt="Second slide"
        />
        <Carousel.Caption>
          <h3>Premium Gift</h3>
          <p>Order Natra + 3 màu bất kỳ</p>
        </Carousel.Caption>
      </Carousel.Item>

      <Carousel.Item>
        <img
          className="d-block w-100"
          src="image-url-3.jpg" // Thay thế bằng URL hình ảnh sản phẩm
          alt="Third slide"
        />
        <Carousel.Caption>
          <h3>Basic Gift</h3>
          <p>Order Natra + 1 màu bất kỳ</p>
        </Carousel.Caption>
      </Carousel.Item> */}
    </Carousel>
  );
}

export default Slideshow;
