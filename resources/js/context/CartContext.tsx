import { createContext, useContext, useState, useEffect } from 'react';
import { getCart } from '../services/CartService';

interface CartContextType {
    cartItemCount: number;
    updateCartCount: () => Promise<void>;
}

const CartContext = createContext<CartContextType>({
    cartItemCount: 0,
    updateCartCount: async () => { }
});

export const useCart = () => useContext(CartContext);

export const CartProvider = ({ children }: { children: React.ReactNode }) => {
    const [cartItemCount, setCartItemCount] = useState(0);

    const updateCartCount = async () => {
        try {
            const res = await getCart();
            if (res?.data?.data) {
                setCartItemCount(res.data.data.length);
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    };

    useEffect(() => {
        updateCartCount();
    }, []);

    return (
        <CartContext.Provider value={{ cartItemCount, updateCartCount }}>
            {children}
        </CartContext.Provider>
    );
};