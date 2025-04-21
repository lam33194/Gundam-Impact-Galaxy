import { useState, useEffect } from 'react';
import { Modal, Button, Form } from 'react-bootstrap';
import { toast } from 'react-toastify';
import { getProvinces, getDistricts, getWards } from '../services/LocationService';
import { Province, District, Ward } from '../interfaces/Location';
import { createAddress, updateAddress, Address } from '../services/AddressService';

interface AddressModalProps {
    show: boolean;
    onHide: () => void;
    address?: Address;
    onSuccess: () => void;
}

const AddressModal = ({ show, onHide, address, onSuccess }: AddressModalProps) => {
    const [formData, setFormData] = useState({
        address: '',
        ward: '',
        district: '',
        city: '',
        is_primary: false
    });

    const [provinces, setProvinces] = useState<Province[]>([]);
    const [districts, setDistricts] = useState<District[]>([]);
    const [wards, setWards] = useState<Ward[]>([]);

    const [selectedProvince, setSelectedProvince] = useState<string>('');
    const [selectedDistrict, setSelectedDistrict] = useState<string>('');
    const [selectedWard, setSelectedWard] = useState<string>('');

    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        const fetchProvinces = async () => {
            try {
                const response = await getProvinces();
                setProvinces(response.data);
            } catch (error) {
                console.error('Failed to fetch provinces:', error);
                toast.error('Không thể tải danh sách tỉnh/thành phố');
            }
        };
        fetchProvinces();
    }, []);

    useEffect(() => {
        if (address) {
            setFormData({
                address: address.address,
                ward: address.ward,
                district: address.district,
                city: address.city,
                is_primary: address.is_primary === 1
            });
            // Load location data based on existing address
            loadLocationData(address.city, address.district, address.ward);
        }
    }, [address]);

    useEffect(() => {
        if (selectedProvince) {
            const fetchDistricts = async () => {
                try {
                    const response = await getDistricts(selectedProvince);
                    setDistricts(response.data);
                } catch (error) {
                    console.error('Failed to fetch districts:', error);
                }
            };
            fetchDistricts();
        } else {
            setDistricts([]);
            setWards([]);
        }
    }, [selectedProvince]);

    useEffect(() => {
        if (selectedDistrict) {
            const fetchWards = async () => {
                try {
                    const response = await getWards(selectedDistrict);
                    setWards(response.data);
                } catch (error) {
                    console.error('Failed to fetch wards:', error);
                }
            };
            fetchWards();
        } else {
            setWards([]);
        }
    }, [selectedDistrict]);

    const loadLocationData = async (city: string, district: string, ward: string) => {
        try {
            const matchingProvince = provinces.find(p => p.name === city);
            if (matchingProvince) {
                setSelectedProvince(matchingProvince.id);

                const districtsResponse = await getDistricts(matchingProvince.id);
                setDistricts(districtsResponse.data);

                const matchingDistrict = districtsResponse.data.find(d => d.name === district);
                if (matchingDistrict) {
                    setSelectedDistrict(matchingDistrict.id);

                    const wardsResponse = await getWards(matchingDistrict.id);
                    setWards(wardsResponse.data);

                    const matchingWard = wardsResponse.data.find(w => w.name === ward);
                    if (matchingWard) {
                        setSelectedWard(matchingWard.id);
                    }
                }
            }
        } catch (error) {
            console.error('Failed to load location data:', error);
        }
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        if (!formData.address || !selectedWard || !selectedDistrict || !selectedProvince) {
            toast.error('Vui lòng điền đầy đủ thông tin');
            return;
        }

        setIsLoading(true);
        try {
            const submitData = {
                ...formData,
                ward: wards.find(w => w.id === selectedWard)?.name || '',
                district: districts.find(d => d.id === selectedDistrict)?.name || '',
                city: provinces.find(p => p.id === selectedProvince)?.name || ''
            };

            if (address?.id) {
                await updateAddress(address.id, submitData);
                toast.success('Cập nhật địa chỉ thành công');
            } else {
                await createAddress(submitData);
                toast.success('Thêm địa chỉ mới thành công');
            }

            onSuccess();
            onHide();
            resetForm();
        } catch (error: any) {
            console.error('Failed to save address:', error);
            const errorMessage = error.response?.data?.message || 'Đã có lỗi xảy ra. Vui lòng thử lại.';
            toast.error(errorMessage);
        } finally {
            setIsLoading(false);
        }
    };

    const resetForm = () => {
        setFormData({
            address: '',
            ward: '',
            district: '',
            city: '',
            is_primary: false
        });
        setSelectedProvince('');
        setSelectedDistrict('');
        setSelectedWard('');
    };

    return (
        <Modal show={show} onHide={onHide} centered>
            <Modal.Header closeButton>
                <Modal.Title>{address ? 'Chỉnh sửa địa chỉ' : 'Thêm địa chỉ mới'}</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <Form id="addressForm" onSubmit={handleSubmit}>
                    <Form.Group className="mb-3">
                        <Form.Label>Tỉnh/Thành phố</Form.Label>
                        <Form.Select
                            value={selectedProvince}
                            onChange={(e) => setSelectedProvince(e.target.value)}
                            required
                        >
                            <option value="">Chọn Tỉnh/Thành phố</option>
                            {provinces.map((province) => (
                                <option key={province.id} value={province.id}>
                                    {province.name}
                                </option>
                            ))}
                        </Form.Select>
                    </Form.Group>

                    <Form.Group className="mb-3">
                        <Form.Label>Quận/Huyện</Form.Label>
                        <Form.Select
                            value={selectedDistrict}
                            onChange={(e) => setSelectedDistrict(e.target.value)}
                            required
                            disabled={!selectedProvince}
                        >
                            <option value="">Chọn Quận/Huyện</option>
                            {districts.map((district) => (
                                <option key={district.id} value={district.id}>
                                    {district.name}
                                </option>
                            ))}
                        </Form.Select>
                    </Form.Group>

                    <Form.Group className="mb-3">
                        <Form.Label>Phường/Xã</Form.Label>
                        <Form.Select
                            value={selectedWard}
                            onChange={(e) => setSelectedWard(e.target.value)}
                            required
                            disabled={!selectedDistrict}
                        >
                            <option value="">Chọn Phường/Xã</option>
                            {wards.map((ward) => (
                                <option key={ward.id} value={ward.id}>
                                    {ward.name}
                                </option>
                            ))}
                        </Form.Select>
                    </Form.Group>

                    <Form.Group className="mb-3">
                        <Form.Label>Địa chỉ</Form.Label>
                        <Form.Control
                            type="text"
                            value={formData.address}
                            onChange={(e) => setFormData(prev => ({ ...prev, address: e.target.value }))}
                            placeholder="Nhập địa chỉ cụ thể"
                            required
                        />
                    </Form.Group>
                </Form>
            </Modal.Body>
            <Modal.Footer>
                <Button variant="secondary" onClick={onHide}>
                    Huỷ bỏ
                </Button>
                <Button
                    variant="primary"
                    type="submit"
                    form="addressForm"
                    disabled={isLoading}
                >
                    {isLoading ? 'Đang lưu...' : 'Lưu địa chỉ'}
                </Button>
            </Modal.Footer>
        </Modal>
    );
};

export default AddressModal;