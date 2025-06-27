import { Card, CardContent } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { JSX } from 'react';

export interface CardProps {
    title: string;
    active_power: number;
    type: string;
    progress: number;
    icon: {
        component: JSX.Element;
        color: string;
    };
}

const MonitoringCard = ({ title, active_power, type, progress, icon }: CardProps) => {
    const status = type === 'production' ? 'Producing' : 'Consuming';

    return (
        <Card>
            <CardContent className="space-y-4">
                <div className="flex justify-between">
                    <div className="flex gap-x-4">
                        <span className={`grid size-12 place-items-center rounded-lg text-white ${icon.color ?? 'bg-blue-600'}`}>
                            {icon.component}
                        </span>

                        <div>
                            <h3 className="text-xl font-bold">{title}</h3>
                            <p className="text-sm text-gray-700">{status}</p>
                        </div>
                    </div>

                    <div className="text-xl font-bold" style={{ fontFamily: 'Inter, sans-serif' }}>
                        {active_power} W
                    </div>
                </div>

                <Progress
                    value={progress}
                    className={`h-2 ${type === 'production' ? 'bg-green-500/20' : 'bg-blue-500/20'}`}
                    indicatorClassName={type === 'production' ? 'bg-green-500' : 'bg-blue-500'}
                />
            </CardContent>
        </Card>
    );
};

export default MonitoringCard;
