import React from 'react';

interface InfoFieldProps {
    label: string;
    id: string;
    type?: string;
    value: string;
    onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
    disabled?: boolean;
}

const InfoField = React.memo(({
    label,
    id,
    type = "text",
    value,
    onChange,
    disabled = false
}: InfoFieldProps) => (
    <div className="mb-3">
        <label htmlFor={id} className="form-label">{label}</label>
        <input
            type={type}
            className="form-control rounded-3"
            id={id}
            value={value}
            onChange={onChange}
            disabled={disabled}
            autoComplete="off"
        />
    </div>
));

InfoField.displayName = 'InfoField';

export default InfoField;