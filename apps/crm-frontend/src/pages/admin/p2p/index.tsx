import { useEffect, useState } from 'react';
import { Routes, Route } from 'react-router-dom';
import {
  listPaymentMethods,
  togglePaymentMethod,
  PaymentMethod,
  listAds,
  toggleAd,
  P2PAd,
  listTrades,
  completeTrade,
  P2PTrade
} from '../../../api/p2p';

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

function Ads() {
  const [ads, setAds] = useState<P2PAd[]>([]);
  useEffect(() => {
    listAds().then(setAds);
  }, []);

  const toggle = async (ad: P2PAd) => {
    await toggleAd(ad.id);
    setAds(ads.map(a => a.id === ad.id ? { ...a, status: a.status ? 0 : 1 } : a));
  };

  return (
    <div>
      <h2>Ads</h2>
      {ads.map(a => (
        <div key={a.id} style={{ marginBottom: 8 }}>
          Ad #{a.id} - {a.status ? 'Active' : 'Inactive'}
          <button onClick={() => toggle(a)} style={{ marginLeft: 8 }}>
            {a.status ? 'Disable' : 'Enable'}
          </button>
        </div>
      ))}
    </div>
  );
}

function Trades() {
  const [trades, setTrades] = useState<P2PTrade[]>([]);
  useEffect(() => {
    listTrades().then(setTrades);
  }, []);

  const complete = async (t: P2PTrade) => {
    await completeTrade(t.id);
    setTrades(trades.map(tr => tr.id === t.id ? { ...tr, status: 1 } : tr));
  };

  return (
    <div>
      <h2>Trades</h2>
      {trades.map(t => (
        <div key={t.id} style={{ marginBottom: 8 }}>
          Trade #{t.id} - Status {t.status}
          {t.status !== 1 && (
            <button onClick={() => complete(t)} style={{ marginLeft: 8 }}>
              Complete
            </button>
          )}
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
        <Route path="ads" element={<Ads />} />
        <Route path="trades" element={<Trades />} />
        <Route path="*" element={<PaymentMethods />} />
      </Routes>
    </div>
  );
}
