import { Card, CardContent } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import React from 'react';

export interface Circuit {
    name: string;
    power: number;
    on: boolean;
    type: 'production' | 'consumption';
    icon?: React.ReactNode;
    color?: string;
}

const MonitoringCard = ({ name, power, icon, color, type }: Circuit) => {
    const percentage = Math.min((power / 1000) * 100, 100);
    const status = type === 'production' ? 'Producing' : 'Consuming';

    return (
        <Card>
            <CardContent className="space-y-4">
                <div className="flex justify-between">
                    <div className="flex gap-x-4">
                        <span className={`grid size-12 place-items-center rounded-lg text-white ${color ?? 'bg-blue-600'}`}>{icon}</span>

                        <div>
                            <h3 className="text-xl font-bold">{name}</h3>
                            <p className="text-sm text-gray-700">{status}</p>
                        </div>
                    </div>

                    <div className="text-xl font-bold" style={{ fontFamily: 'Inter, sans-serif' }}>
                        {power} W
                    </div>
                </div>

                <Progress
                    value={percentage}
                    className={`h-2 ${type === 'production' ? 'bg-green-500/20' : 'bg-blue-500/20'}`}
                    indicatorClassName={type === 'production' ? 'bg-green-500' : 'bg-blue-500'}
                />
            </CardContent>
        </Card>
    );
};

export default MonitoringCard;
