import { Modal, Button } from 'react-bootstrap';
import './ConfirmModal.scss';

interface ConfirmModalProps {
    show: boolean;
    onHide: () => void;
    onConfirm: () => void;
    title: string;
    message: string;
    isLoading?: boolean;
    confirmText?: string;
    loadingText?: string;
    confirmVariant?: string;
    size?: 'sm' | 'lg' | 'xl';
}

const ConfirmModal = ({
    show,
    onHide,
    onConfirm,
    title,
    message,
    isLoading,
    confirmText = 'Xác nhận',
    loadingText = 'Đang xử lý...',
    confirmVariant = 'primary',
    size = 'lg'
}: ConfirmModalProps) => {
    return (
        <Modal
            show={show}
            onHide={onHide}
            centered
            size={size}
            className="confirm-modal"
        >
            <Modal.Header className="border-0">
                <Modal.Title className="fw-bold fs-4">{title}</Modal.Title>
            </Modal.Header>
            <Modal.Body className="py-4">
                <p className="mb-0 fs-5 text-secondary">{message}</p>
            </Modal.Body>
            <Modal.Footer className="border-0">
                <Button
                    variant="outline-secondary"
                    onClick={onHide}
                    className="px-4"
                >
                    Huỷ bỏ
                </Button>
                <Button
                    variant={confirmVariant}
                    onClick={onConfirm}
                    disabled={isLoading}
                    className="px-4"
                >
                    {isLoading ? (
                        <>
                            <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            {loadingText}
                        </>
                    ) : confirmText}
                </Button>
            </Modal.Footer>
        </Modal>
    );
};

export default ConfirmModal;