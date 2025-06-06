import { Card, CardContent } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import React from 'react';

export interface MonitoringCardProps {
    title: string;
    power: number;
    status: string;
    type: 'production' | 'consumption';
    icon?: React.ReactNode;
    color?: string;
}

const MonitoringCard = ({ title, power, status, icon, color, type }: MonitoringCardProps) => {
    const percentage = Math.min((power / 1000) * 100, 100); // arbitrary max scale

    return (
        <Card>
            <CardContent className="space-y-4">
                <div className="flex justify-between">
                    <div className="flex gap-x-4">
                        <span className={`grid size-12 place-items-center rounded-lg text-white ${color ?? 'bg-blue-600'}`}>{icon}</span>

                        <div>
                            <h3 className="text-xl font-bold">{title}</h3>
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
