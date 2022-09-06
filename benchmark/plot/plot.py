import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import shutil
import os

current_folder = os.path.dirname(os.path.realpath(__file__))

# make Fira Code available
shutil.copy(current_folder+'/FiraCode-Regular.ttf', '/usr/local/share/fonts')
os.system('fc-cache -f')
os.system('rm ~/.cache/matplotlib -rf')

# change global matplotlib settings
plt.rcParams.update({'font.family': 'Fira Code'})
plt.rcParams.update({'text.color': '#34495e'})
plt.rcParams.update({'axes.labelcolor': '#34495e'})
plt.rcParams.update({'xtick.color': '#34495e'})
plt.rcParams.update({'ytick.color': '#34495e'})


def plot_benchmark(df, metric, title, x_label, y_label, plot_path):
    df_zero_offset = df.loc[df['variant_name'] == 0]
    metric_offset_zero = df_zero_offset[metric].mean()

    df_real_runs = df.loc[df['variant_name'] != 0]

    pd.options.mode.chained_assignment = None
    df_real_runs[metric] -= metric_offset_zero
    pd.options.mode.chained_assignment = 'warn'

    x = df_real_runs['variant_name'].to_numpy()
    y = df_real_runs[metric].to_numpy()
    a, b = np.polyfit(x, y, deg=1)
    y_est = a * x + b

    fig, ax = plt.subplots()
    ax.plot(x, y, '.', color='#018bff')
    ax.plot(x, y_est, '-', color='#018bff')
    ax.set_title(title)
    ax.set_xlabel(x_label)
    ax.set_ylabel(y_label)
    fig.savefig(plot_path)


# load benchmark csv
df = pd.read_csv(current_folder+'/../benchmarks.csv', sep=',')
df['variant_name'] = df['variant_name'].astype(int)
df['result_time_avg'] = df['result_time_avg'].astype(float)
df['result_mem_peak'] = df['result_mem_peak'].astype(int)
df['result_time_avg'] = df['result_time_avg'] / 1000
df['result_mem_peak'] = df['result_mem_peak'] / (1024 * 1024)

df = df[['benchmark_name', 'variant_name', 'result_time_avg', 'result_mem_peak']]
df_nodes = df.loc[df['benchmark_name'] == 'NodeBench']
df_relations = df.loc[df['benchmark_name'] == 'RelationBench']

# create plots
plot_benchmark(df_nodes, 'result_time_avg', '#Nodes vs Time', 'Nodes [x]', 'Time [ms]', current_folder+'/../../docs/assets/nodes_time.png')
plot_benchmark(df_nodes, 'result_mem_peak', '#Nodes vs Memory', 'Nodes [x]', 'Memory [MB]', current_folder+'/../../docs/assets/nodes_memory.png')
plot_benchmark(df_relations, 'result_time_avg', '#Relations vs Time', 'Relations [x]', 'Time [ms]', current_folder+'/../../docs/assets/relations_time.png')
plot_benchmark(df_relations, 'result_mem_peak', '#Relations vs Memory', 'Relations [x]', 'Memory [MB]', current_folder+'/../../docs/assets/relations_memory.png')

print('Finished')
