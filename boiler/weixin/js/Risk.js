var myChart = echarts.init(document.getElementById('test1'));
option = {
    tooltip: {},
    silent:true, //是否相应鼠标事件
    radar: {
        // shape: 'circle',
        splitNumber:1, // 雷达图圈数设置
        name: {
            textStyle: {
                color: '#485465',
                fontSize:16,
            }
        },
        splitArea:{
            areaStyle:{
                color:'#fff'
            },

        },
        splitLine: {
            lineStyle: {
                color: 'rgba(193, 198, 206,1)',
            }
        },
        indicator: [
            { name: '', max: 100},
            { name: '', max: 100},
            { name: '', max: 100},
            { name: '', max: 100},
            { name: '', max: 100},
        ],
        center: ['50%', '50%'],
        radius:'100%'
    },
    series: [{
        type: 'radar',
        symbolSize:1,// 拐点的大小
        // areaStyle: {normal: {}},
        data : [
            {
                value : [{
                }, 80, 30, 70, 40],
                areaStyle: {
                    normal: {
                        color: 'rgba(49,78, 206,0.5)'
                    }
                },
                itemStyle: {
                    normal: {
                        color: 'rgba(49,78, 206,0.2)',

                    },
                },
            }
        ]
    }]
};
myChart.setOption(option, true);