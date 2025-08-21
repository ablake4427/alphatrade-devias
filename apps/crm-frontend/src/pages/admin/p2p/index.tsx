import { useEffect, useState } from 'react';
import { Routes, Route } from 'react-router-dom';
import { listPaymentMethods, togglePaymentMethod, PaymentMethod } from '../../../api/p2p';

function PaymentMethods() {
  const [methods, setMethods] = useState<PaymentMethod[]>([]);
  useEffect(() => {
    listPaymentMethods().then(setMethods);
  }, []);

  const toggle = async (m: PaymentMethod) => {
    await togglePaymentMethod(m.id, !m.active);
    setMethods(methods.map(pm => pm.id === m.id ? { ...pm, active: !pm.active } : pm));
  };

  return (
    <div>
      <h2>Payment Methods</h2>
      {methods.map(m => (
        <div key={m.id} style={{ marginBottom: 8 }}>
          {m.name} - {m.active ? 'Active' : 'Inactive'}
          <button onClick={() => toggle(m)} style={{ marginLeft: 8 }}>
            {m.active ? 'Disable' : 'Enable'}
          </button>
        </div>
      ))}
    </div>
  );
}

export default function P2PAdmin() {
  return (
    <div>
      <h1>P2P Admin</h1>
      <Routes>
        <Route path="payment-methods" element={<PaymentMethods />} />
        <Route path="*" element={<PaymentMethods />} />
      </Routes>
    </div>
  );
}
