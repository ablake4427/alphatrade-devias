import { useEffect, useState } from 'react';
import { Link, Routes, Route } from 'react-router-dom';
import { listBinaryTrades, getTradeComments, addTradeComment, BinaryTrade, TradeComment } from '../../../api/binary-trades';

function TradeList({ status }: { status: string }) {
  const [trades, setTrades] = useState<BinaryTrade[]>([]);
  useEffect(() => {
    listBinaryTrades(status).then(setTrades);
  }, [status]);
  return (
    <div>
      {trades.map(trade => (
        <TradeItem key={trade.id} trade={trade} />
      ))}
    </div>
  );
}

function TradeItem({ trade }: { trade: BinaryTrade }) {
  const [comments, setComments] = useState<TradeComment[]>([]);
  const [message, setMessage] = useState('');
  useEffect(() => {
    getTradeComments(trade.id).then(setComments);
  }, [trade.id]);
  const add = async () => {
    if (!message.trim()) return;
    await addTradeComment(trade.id, message);
    setMessage('');
    setComments(await getTradeComments(trade.id));
  };
  return (
    <div style={{ border: '1px solid #ccc', padding: 8, marginBottom: 8 }}>
      <pre>{JSON.stringify(trade, null, 2)}</pre>
      <div>
        {comments.map(c => (
          <div key={c.id}>{c.message}</div>
        ))}
      </div>
      <input value={message} onChange={e => setMessage(e.target.value)} placeholder="Add comment" />
      <button onClick={add}>Post</button>
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
