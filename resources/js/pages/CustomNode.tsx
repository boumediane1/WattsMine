import React, { memo } from 'react';
import { Handle, NodeProps, Position } from 'reactflow';

type CustomNodeData = {
    image: string;
    title: string;
    value: string;
};

const CustomNode: React.FC<NodeProps<CustomNodeData>> = ({ data }) => {
    return (
        <div>
            <img src={data.image} className="mx-auto w-12" alt="" />

            <div
                className="mt-1 text-center text-xs font-bold"
                style={{
                    fontFamily: 'Inter, sand-serif',
                }}
            >
                {data.value} W
            </div>

            <Handle type="source" position={Position.Bottom} style={{ opacity: 0 }} />
            <Handle type="target" position={Position.Top} style={{ opacity: 0 }} />
        </div>
    );
};

export default memo(CustomNode);
