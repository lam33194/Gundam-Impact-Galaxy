import { useState, useEffect } from 'react';
import "./Voucher.scss";
import { toast } from "react-toastify";
import { Modal } from 'bootstrap';
import { VoucherProps } from '../interfaces/VoucherProps';
import hotcouponImage from '../assets/hotcoupon.png';

const Voucher = ({ voucher }: VoucherProps) => {
  const [modal, setModal] = useState<Modal | null>(null);

  useEffect(() => {
    const modalElement = document.getElementById(`voucherModal-${voucher.id}`);
    if (modalElement) {
      setModal(new Modal(modalElement));
    }
  }, [voucher.id]);

  const handleCopyCode = () => {
    navigator.clipboard.writeText(voucher.code);
    toast.success("Đã sao chép mã giảm giá!");
  };

  const handleShowDetails = () => {
    modal?.show();
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('vi-VN');
  };

  const formatPrice = (price: string) => {
    return parseInt(price).toLocaleString('vi-VN');
  };

  const isExpired = () => {
    const endDate = new Date(voucher.end_date_time);
    return endDate < new Date();
  };

  const isFullyUsed = () => {
    return voucher.used_count >= voucher.max_usage;
  };

  const getButtonContent = () => {
    if (isExpired()) {
      return {
        text: "Đã hết hạn",
        disabled: true,
        className: "btn-secondary"
      };
    }
    if (isFullyUsed()) {
      return {
        text: "Đã hết lượt",
        disabled: true,
        className: "btn-secondary"
      };
    }
    return {
      text: "Sao chép mã",
      disabled: false,
      className: "btn-dark"
    };
  };

  return (
    <>
      <div className="coupon d-flex gap-3 align-items-center">
        <div className="image p-2">
          <img
            width="70"
            height="70"
            src={hotcouponImage}
            alt={voucher.title}
          />
          <span>{voucher.title}</span>
        </div>
        <div className="content_wrap py-2 px-1 gap-2 d-flex flex-column">
          <div className="content-top d-flex flex-column">
            <span className="fw-bold">NHẬP MÃ: {voucher.code}</span>
            <span>Giảm {formatPrice(voucher.discount)}đ</span>
            {voucher.min_order_amount && (
              <small className="text-muted">
                Đơn tối thiểu {formatPrice(voucher.min_order_amount)}đ
              </small>
            )}
          </div>
          <div className="content-bottom d-flex align-items-center justify-content-between">
            <div
              className={`coupon-code btn ${getButtonContent().className} btn-sm`}
              onClick={getButtonContent().disabled ? undefined : handleCopyCode}
              style={{ cursor: getButtonContent().disabled ? 'not-allowed' : 'pointer' }}
            >
              {getButtonContent().text}
            </div>
            <a
              href="#"
              className="details-link"
              onClick={(e) => {
                e.preventDefault();
                handleShowDetails();
              }}
            >
              Chi tiết
            </a>
          </div>
        </div>
      </div>

      {/* Modal */}
      <div className="modal fade" id={`voucherModal-${voucher.id}`} tabIndex={-1}>
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title">Chi tiết mã giảm giá</h5>
              <button type="button" className="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div className="modal-body">
              <div className="voucher-details">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <p className="mb-0"><strong>Mã giảm giá:</strong> {voucher.code}</p>
                  <button
                    className={`btn ${getButtonContent().className} btn-sm`}
                    onClick={getButtonContent().disabled ? undefined : handleCopyCode}
                    disabled={getButtonContent().disabled}
                  >
                    {!getButtonContent().disabled && <i className="fas fa-copy me-1"></i>}
                    {getButtonContent().text}
                  </button>
                </div>
                <p><strong>Giảm giá:</strong> {formatPrice(voucher.discount)}đ</p>
                {voucher.min_order_amount && (
                  <p><strong>Đơn tối thiểu:</strong> {formatPrice(voucher.min_order_amount)}đ</p>
                )}
                <p><strong>Thời gian:</strong> {formatDate(voucher.start_date_time)} - {formatDate(voucher.end_date_time)}</p>
                <p><strong>Số lượng còn lại:</strong> {voucher.max_usage - voucher.used_count}</p>
                {voucher.description && (
                  <p><strong>Chi tiết:</strong> {voucher.description}</p>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

export default Voucher;
