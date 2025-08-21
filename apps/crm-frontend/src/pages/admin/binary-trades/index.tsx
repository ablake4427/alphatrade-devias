import { useEffect, useState } from 'react';
import { Link, Routes, Route } from 'react-router-dom';
import { listBinaryTrades, refundBinaryTrade, BinaryTrade } from '../../../api/binary-trades';

function TradeList({ status }: { status: string }) {
  const [trades, setTrades] = useState<BinaryTrade[]>([]);
  const load = () => listBinaryTrades(status).then(setTrades);
  useEffect(() => {
    load();
  }, [status]);
  const refund = async (id: string) => {
    await refundBinaryTrade(id);
    load();
  };
  return (
    <div>
      {trades.map(trade => (
        <div key={trade.id} style={{ border: '1px solid #ccc', padding: 8, marginBottom: 8 }}>
          <pre>{JSON.stringify(trade, null, 2)}</pre>
          <button onClick={() => refund(trade.id)}>Refund</button>
        </div>
      ))}
    </div>
  );
}

export default function BinaryTrades() {
  return (
    <div>
      <h1>Binary Trades</h1>
      <nav>
        <Link to="running">Running</Link> |{' '}
        <Link to="win">Win</Link> |{' '}
        <Link to="lose">Lose</Link>
      </nav>
      <Routes>
        <Route path="running" element={<TradeList status="running" />} />
        <Route path="win" element={<TradeList status="win" />} />
        <Route path="lose" element={<TradeList status="lose" />} />
        <Route path="*" element={<TradeList status="running" />} />
      </Routes>
    </div>
  );
}
