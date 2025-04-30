import { createContext, useContext, useState, useEffect } from 'react';
import { getCart } from '../services/CartService';
import { useAuth } from './AuthContext';

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
    const { isAuthenticated } = useAuth();

    const updateCartCount = async () => {
        if (!isAuthenticated) {
            setCartItemCount(0);
            return;
        }

        try {
            const res = await getCart();
            if (res?.data?.data) {
                setCartItemCount(res.data.data.length);
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
            setCartItemCount(0);
        }
    };

    useEffect(() => {
        updateCartCount();
    }, [isAuthenticated]);

    return (
        <CartContext.Provider value={{ cartItemCount, updateCartCount }}>
            {children}
        </CartContext.Provider>
    );
};