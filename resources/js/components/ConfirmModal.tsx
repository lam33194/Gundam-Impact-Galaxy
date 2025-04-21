import { Modal, Button } from 'react-bootstrap';

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
    confirmVariant = 'primary'
}: ConfirmModalProps) => {
    return (
        <Modal show={show} onHide={onHide} centered size="sm">
            <Modal.Header closeButton>
                <Modal.Title>{title}</Modal.Title>
            </Modal.Header>
            <Modal.Body>{message}</Modal.Body>
            <Modal.Footer>
                <Button variant="secondary" onClick={onHide}>
                    Huỷ bỏ
                </Button>
                <Button
                    variant={confirmVariant}
                    onClick={onConfirm}
                    disabled={isLoading}
                >
                    {isLoading ? loadingText : confirmText}
                </Button>
            </Modal.Footer>
        </Modal>
    );
};

export default ConfirmModal;